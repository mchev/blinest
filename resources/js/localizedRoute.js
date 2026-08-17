export function createLocalizedRoute(route) {
  return function localizedRoute(name, params, absolute, config) {
    const page = this?.$page ?? this?.page
    const locale = page?.props?.locale ?? page?.props?.default_locale ?? 'fr'
    const defaultLocale = page?.props?.default_locale ?? 'fr'
    const ziggy = config ?? page?.props?.ziggy

    if (locale !== defaultLocale) {
      const localizedName = `${locale}.${name}`

      if (ziggy?.routes?.[localizedName]) {
        return route(localizedName, params, absolute, ziggy)
      }
    }

    return route(name, params, absolute, ziggy)
  }
}
