export function useArrayKey() {
  return {
    array_unique_key: () => Math.random().toString(16).slice(2),
  }
}
