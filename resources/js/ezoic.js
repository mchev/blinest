const excludedPathPatterns = [
    /^\/rooms(?:\/|$)/,
    /^\/login$/,
    /^\/register$/,
    /^\/auth\//,
    /^\/callback\//,
    /^\/guest\//,
    /^\/minigames(?:\/|$)/,
    /^\/admin(?:\/|$)/,
    /^\/moderation(?:\/|$)/,
    /^\/user\/banned$/,
    /^\/create\/room$/,
    /^\/playlists\/[^/]+\/edit$/,
];

export function shouldServeEzoicAds(path) {
    return ! excludedPathPatterns.some((pattern) => pattern.test(path));
}

export function syncEzoicAds(path) {
    if (typeof window.ezstandalone === 'undefined') {
        return;
    }

    window.ezstandalone.cmd.push(function () {
        if (! shouldServeEzoicAds(path)) {
            if (typeof window.ezstandalone.setEzoicAnchorAd === 'function') {
                window.ezstandalone.setEzoicAnchorAd(false);
            }

            if (typeof window.ezstandalone.destroyAll === 'function') {
                window.ezstandalone.destroyAll();
            }

            return;
        }

        if (typeof window.ezstandalone.setEzoicAnchorAd === 'function') {
            window.ezstandalone.setEzoicAnchorAd(false);
        }
    });
}
