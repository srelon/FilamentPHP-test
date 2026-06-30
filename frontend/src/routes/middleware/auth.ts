export default function auth({ next, store }: any) {
    if (!store.user) {
        return next({ name: 'home' })
    }

    return next()
}
