---
name: player-gameplay
description: "Use this skill whenever modifying room gameplay, the audio player, track timing, Echo realtime events, inter-track transitions, scoring during playback, or related Vue partials (Player, Show, UserInput, Answers, Ranking). Production-critical: ~400k users, millions of tracks, high traffic. Server jobs drive the track chain; client audio is display-only. Read before any player refactor."
license: MIT
metadata:
  author: blinest
---

# Player & Room Gameplay — Maintenance Guidelines

**Read this skill before changing anything in the player stack.** This area has caused production regressions. Changes are high blast-radius.

## Scale & stakes

- ~400k registered users; rooms can be very active during peak hours.
- Track chain, scoring, and broadcasts run on **every room in play** — small inefficiencies multiply fast.
- Horizon must be running in every environment where rounds advance.
- Assume **slow clients, reconnects, iOS autoplay limits, and clock skew** — not ideal desktop Chrome.

## Source of truth (non-negotiable)

| Concern | Authority | Client role |
|---------|-----------|-------------|
| When track ends | `ProcessTrackPlayed` job → `TrackEnded` | React to event; do not end the round locally |
| When next track starts | `ProcessTrackEnded` → `playNextTrack()` → `TrackPlayed` | Start playback on event |
| Inter-track pause | `room.pause_between_tracks` + queued delay | Countdown UI only |
| Scores during round | Redis via `RoundScoreService` | Submit answers; display from `NewScore` |
| User list in room | Echo presence (`.here` / `.joining` / `.leaving`) | Never invent users from REST alone |

**Never add a parallel client-side clock that competes with server jobs** (e.g. polling `/time` every track, client-only track chaining). That caused drift, duplicate handlers, and player freezes in past refactors.

## Architecture (minimal)

```
playNextTrack() → TrackPlayed (broadcast) + ProcessTrackPlayed.delay(track_duration)
ProcessTrackPlayed → TrackEnded (broadcast) + ProcessTrackEnded.delay(pause_between_tracks)
ProcessTrackEnded → playNextTrack() or round.stop()
```

**Track index:** after `playNextTrack()`, `round.current` is 1-based index of the *next* slot. Playing track = `tracks[current - 1]`.

## Files that must change together

| If you change… | Also review |
|----------------|-------------|
| `Round.php` `playNextTrack` / `scheduleTrackEndJob` | `ProcessTrackPlayed`, `ProcessTrackEnded`, `TrackPlayed`, `TrackEnded` |
| `TrackPlayed` / `TrackEnded` payload | `Show.vue`, `Player.vue`, `UserInput.vue`, `Answers.vue` listeners |
| Join / reconnect hydration | `RoomController::joined`, `Show.vue` `hydrateRoomFromJoinedPayload` |
| Answer timing / speed bonus | `RoundController::check`, `UserInput.vue` `currentTime` |
| Playlist cards | `Answers.vue`, `TrackEnded` broadcast shape, `joined` `playedTracks` |
| Room timing settings | `Room` model, `EditOptionsForm.vue`, job delays in `Round.php` |

## Echo rules (frequent bug source)

1. **`Show.vue` owns `Echo.leave(channel)`** — children must only `stopListening`, never leave.
2. **Multiple `Echo.join` on the same channel:** Show, Player, UserInput, Answers, Controls each join. Adding a listener in one place does not remove the need to audit others.
3. **Do not handle the same event in both parent props and child Echo** unless the contract is explicit (e.g. Show clears `initialTrack` on `TrackPlayed`; Player must handle subsequent tracks via its own listener).
4. **Always `stopListening` before re-registering** (Player does this; UserInput/Answers do not — do not add more listeners without cleanup).
5. **Stale events:** guard with track id + `round.current` / sequence before applying `TrackEnded` after a new track started.

## Client player rules

1. **One playback path per track change** — avoid mount + watch + Echo all calling `play()` without deduplication (`lastAppliedTrackKey` or equivalent).
2. **Do not call `beginAudioPlayback` on stale `readyState`** after `audio.src` change — wait for `canplaythrough` or use a generation token.
3. **Preload next track cautiously** — separate muted `Audio` element; `metadata` only until ~2s before transition; never block the main element's stream.
4. **Avoid destroying the waveform canvas** (`v-if` loading) during inter-track wait — causes blank UI and stale canvas context.
5. **`track:ended` emit from Player is unused** — inter-track flow must come from server `TrackEnded`, not local `audio ended` alone.

## Server / performance rules

1. **`playNextTrack` HTTP probe** — non-YouTube/local tracks trigger `Http::get($audioUrl)` server-side. Do not add more per-track HTTP from the client for the same purpose.
2. **`POST /check`** — throttled 60/min; each call touches Redis multiple times. Do not add polling that hits check or listened endpoints.
3. **`POST /listened`** — already sent from Show (join) and Player (track start). Do not add a third caller without idempotency.
4. **`GET /joined`** — loads round, track, played history. Keep payload lean; avoid N+1 on millions-scale track tables.
5. **Queue jobs** — use `Queue::fake()` in tests; never run the real chain synchronously in PHPUnit without faking (infinite loop risk).
6. **Do not add high-frequency endpoints** (e.g. `/rooms/{id}/time` every few seconds per client in active rooms).

## Pre-change checklist

Before merging any player/timing change:

- [ ] Draw the event timeline: TrackPlayed → play → TrackEnded → countdown → next TrackPlayed.
- [ ] List every file that listens to `TrackPlayed` / `TrackEnded`.
- [ ] Confirm Horizon processes `ProcessTrackPlayed` and `ProcessTrackEnded`.
- [ ] Test **mid-join** (open room while track is playing).
- [ ] Test **track 2+** (not only first track).
- [ ] Test **reconnect** (disable network briefly, resync).
- [ ] Test **iOS / mobile** if touching audio start or gestures.
- [ ] Run feature tests: `RoomJoinedIncludesPlayedTracksTest`, `TrackAnswerAliasScoringTest`, `RoundScoreDuplicatePreventionTest`.
- [ ] Add or restore a **track chain test** with `Queue::fake()` if touching jobs.
- [ ] Prefer **small PRs** — UI polish separate from timing/scoring/Echo contract changes.

## Testing patterns

```php
// Track chain — always fake the queue
Queue::fake();
(new ProcessTrackPlayed($round))->handle();
Queue::assertPushed(ProcessTrackEnded::class);
```

```php
// Time travel for deadlines
Carbon::setTestNow($round->current_track_started_at->addSeconds($room->track_duration));
```

No automated frontend tests exist for Player/Show — **manual QA in a real room with 2+ tracks is mandatory**.

## Safe change patterns

| Goal | Safer approach |
|------|----------------|
| Smoother countdown UI | CSS/animation only; keep server `next_track_at` unchanged |
| Better waveform | Client-only canvas/analyser; no new API calls |
| Fix scoring | `RoundController` + Redis tests; don't touch Player |
| Fix playlist empty cards | `TrackEnded` payload + `Answers.vue` only |
| Sync progress bar | Derive from `currentTime` prop or server `startTime` on join — not a new global clock |

## Rollback plan

Player regressions hit users immediately. For any non-trivial change:

1. Keep backend and frontend deployable independently where possible.
2. Feature-flag risky UI if the stack supports it.
3. Know the last known-good commit for `Player.vue` / `Show.vue`.
4. Monitor: Horizon failed jobs, Reverb errors, spike in `/check` 409s, user reports of "stuck between tracks".

## Reference files

- `resources/js/Pages/Rooms/Show.vue` — orchestration, join, presence
- `resources/js/Pages/Rooms/partials/Player.vue` — audio/YouTube playback
- `resources/js/Pages/Rooms/partials/UserInput.vue` — answers
- `resources/js/Pages/Rooms/partials/Answers.vue` — playlist
- `app/Models/Round.php` — `playNextTrack`, `stop`
- `app/Jobs/ProcessTrackPlayed.php`, `app/Jobs/ProcessTrackEnded.php`
- `app/Events/TrackPlayed.php`, `app/Events/TrackEnded.php`
- `app/Http/Controllers/RoundController.php` — `check`
- `app/Services/RoundScoreService.php` — Redis scoring
