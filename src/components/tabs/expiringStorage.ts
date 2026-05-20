interface CachedItem<T> {
  value: T
  expires: string
}

class ExpiringStorage {
  get<T>(key: string): T | null {
    const raw = localStorage.getItem(key)
    if (!raw) return null
    const cached: CachedItem<T> = JSON.parse(raw)
    if (new Date(cached.expires) < new Date()) {
      localStorage.removeItem(key)
      return null
    }
    return cached.value
  }

  set<T>(key: string, value: T, lifeTimeInMinutes: number): void {
    const expires = new Date(Date.now() + lifeTimeInMinutes * 60000)
    localStorage.setItem(key, JSON.stringify({ value, expires }))
  }
}

export default new ExpiringStorage()
