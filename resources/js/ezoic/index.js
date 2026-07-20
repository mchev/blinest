const excludedPathPatterns = [
    /^\/login$/,
    /^\/register$/,
    /^\/auth\//,
    /^\/callback\//,
    /^\/guest\//,
    /^\/admin(?:\/|$)/,
    /^\/moderation(?:\/|$)/,
    /^\/user\/banned$/,
    /^\/create\/room$/,
    /^\/playlists\/[^/]+\/edit$/,
    /^\/rooms\/[^/]+\/edit$/,
    /^\/minigames\/(quiz|who-sang|anagram|first-letter|album-cover)$/,
];

/** Room lobby toggles ads locally based on gameplay state. */
const roomManagedPathPattern = /^\/rooms\/[^/]+$/;

/** Anchor ads are allowed only on low-interaction, content-first pages. */
const anchorAdPathPatterns = [
    /^\/$/,
    /^\/docs\/faq$/,
    /^\/rankings(?:\/|$)/,
    /^\/pages\//,
];

export function shouldServeEzoicAds(path) {
    return ! excludedPathPatterns.some((pattern) => pattern.test(path));
}

export function isRoomManagedAdPath(path) {
    return roomManagedPathPattern.test(path);
}

function shouldEnableAnchorAd(path) {
    return anchorAdPathPatterns.some((pattern) => pattern.test(path));
}

function collectVisiblePlacementIds() {
    return Array.from(document.querySelectorAll('[id^="ezoic-pub-ad-placeholder-"]'))
        .map((element) => {
            const match = element.id.match(/ezoic-pub-ad-placeholder-(\d+)/);

            return match ? Number(match[1]) : null;
        })
        .filter((id) => id !== null);
}

function runEzoicCommand(callback) {
    if (typeof window.ezstandalone === 'undefined') {
        return;
    }

    window.ezstandalone.cmd.push(callback);
}

export function clearEzoicAds() {
    runEzoicCommand(function () {
        if (typeof window.ezstandalone.setEzoicAnchorAd === 'function') {
            window.ezstandalone.setEzoicAnchorAd(false);
        }

        if (typeof window.ezstandalone.destroyAll === 'function') {
            window.ezstandalone.destroyAll();
        }
    });
}

export function syncEzoicAds(path, { force = false } = {}) {
    if (! shouldServeEzoicAds(path)) {
        clearEzoicAds();

        return;
    }

    if (isRoomManagedAdPath(path) && ! force) {
        return;
    }

    runEzoicCommand(function () {
        if (typeof window.ezstandalone.setEzoicAnchorAd === 'function') {
            window.ezstandalone.setEzoicAnchorAd(! force && shouldEnableAnchorAd(path));
        }

        const placementIds = collectVisiblePlacementIds();

        if (placementIds.length > 0) {
            window.ezstandalone.showAds(...placementIds);
        } else {
            window.ezstandalone.showAds();
        }
    });
}

export function scheduleEzoicSync(path, options = {}) {
    requestAnimationFrame(() => {
        requestAnimationFrame(() => syncEzoicAds(path, options));
    });
}

export { EZOIC } from './placements';
