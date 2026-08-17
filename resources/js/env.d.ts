/// <reference types="vite/client" />

declare module '*.vue' {
  import type { DefineComponent } from 'vue'

  const component: DefineComponent<object, object, unknown>

  export default component
}

declare module 'vue' {
  interface ComponentCustomProperties {
    __: (key: string, replace?: Record<string, string>) => string
    route: (name: string, params?: unknown, absolute?: boolean, config?: unknown) => string
  }
}

export {}
