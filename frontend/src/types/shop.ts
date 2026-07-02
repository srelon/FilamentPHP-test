export interface ProductSummary {
    slug: string
    title: string
    author: string | null
    category: string | null
    price: string | null
    rating: number
    image: string | null
}

export interface AuthorSummary {
    name: string
    slug: string
    content: string | null
    photo: string | null
}

export interface BlogPostSummary {
    title: string
    slug: string
    date: string | null
    image: string | null
    category: string | null
}
