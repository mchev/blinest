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
  /^\/rooms\/[^/]+$/,
  /^\/rooms\/[^/]+\/edit$/,
  /^\/minigames\/(quiz|who-sang|anagram|first-letter|album-cover)$/,
]

export function shouldServeAds(path, { adsDisabled = false } = {}) {
  if (adsDisabled) {
    return false
  }

  return !excludedPathPatterns.some((pattern) => pattern.test(path))
}

export function pushAdsenseSlot() {
  try {
    (window.adsbygoogle = window.adsbygoogle || []).push({})
  } catch {
    // AdSense script may be blocked or not loaded (donors, goal reached).
  }
}
