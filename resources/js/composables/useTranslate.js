import { usePage } from '@inertiajs/vue3'

export function useTranslate() {
  const page = usePage()

  return (key, replace = {}) => {
    const dictionary = page.props.language ?? {}
    let translation = dictionary[key] ?? key

    Object.entries(replace).forEach(([placeholder, value]) => {
      translation = translation.replace(`:${placeholder}`, String(value))
    })

    return translation
  }
}
