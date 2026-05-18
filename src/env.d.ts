/// <reference types="vite/client" />

declare const $_: (key: string) => string

declare module 'dateformat' {
  function dateformat(date: Date | string | number, mask: string, utc?: boolean): string
  export = dateformat
}

declare module 'lodash.tonumber' {
  function toNumber(value: unknown): number
  export = toNumber
}

declare module 'number-formatter' {
  function format(mask: string, value: number): string
  export = format
}
