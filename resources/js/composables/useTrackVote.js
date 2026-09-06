export function applyTrackVotePayload(track, payload) {
  if (!track || !payload) {
    return
  }

  track.upvotes = payload.upvotes
  track.downvotes = payload.downvotes
  track.user_voted_up = payload.user_voted_up
  track.user_voted_down = payload.user_voted_down
}
