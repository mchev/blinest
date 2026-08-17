<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class LlmsTxtController extends Controller
{
    public function __invoke(): Response
    {
        $baseUrl = rtrim(config('app.url'), '/');

        $content = <<<MD
# Blinest

> Blinest is a free online multiplayer music quiz platform (blind tests). Players join themed rooms, listen to audio clips and identify songs in real time against other players.

Blinest runs official public rooms (80s, 2000s, Disney, French songs, rock, pop, rap…) and community-created private rooms. Free to play in the browser, no download. Available in French, English and Spanish. Primary audience: French-speaking music fans in France and worldwide.

## Core pages

- [Home]({$baseUrl}/): Public and private rooms, mini-games, room search.
- [How to play]({$baseUrl}/docs/howto): Step-by-step guide to join a room and play a blind test.
- [FAQ]({$baseUrl}/docs/faq): Frequently asked questions about accounts, rooms, scores and rules.
- [Glossary]({$baseUrl}/docs/glossary): Definitions of blind test, room, round, track, score, level, ELO, XP, playlist.

## Progression & docs

- [Progression overview]({$baseUrl}/docs): Level, score, ELO and XP systems.
- [Level system]({$baseUrl}/docs/level): How XP and levels work.
- [ELO system]({$baseUrl}/docs/elo): Competitive ranking explained.
- [Create rooms & playlists]({$baseUrl}/docs/create-content): Guide for hosts and playlist creators.

## Optional

- [Contact]({$baseUrl}/contact): Contact the Blinest team.
- [Terms & legal pages]({$baseUrl}/pages/cgu): Legal information (French).

MD;

        return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
