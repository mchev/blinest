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
];

export function shouldServeEzoicAds(path) {
    return ! excludedPathPatterns.some((pattern) => pattern.test(path));
}

function runEzoicCommand(callback) {
    if (typeof window.ezstandalone === 'undefined') {
        return;
    }

    window.ezstandalone.cmd.push(callback);
}

export function syncEzoicAds(path) {
    if (! shouldServeEzoicAds(path)) {
        runEzoicCommand(function () {
            if (typeof window.ezstandalone.destroyAll === 'function') {
                window.ezstandalone.destroyAll();
            }
        });

        return;
    }

    runEzoicCommand(function () {
        window.ezstandalone.showAds();
    });
}

export function scheduleEzoicSync(path) {
    requestAnimationFrame(() => {
        requestAnimationFrame(() => syncEzoicAds(path));
    });
}

export { EZOIC } from './placements';
