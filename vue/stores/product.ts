import { defineStore } from "pinia"

type Product = {
	picture: string
	id: number
	name: string
	price: number
}

const useProductStore = defineStore({
	id: "product",
	state: () => ({
		products: [] as Product[],
		counter: 0
	}),
	actions: {
		async init() {
			try {
				const res = await fetch("/api/products?page=1")
				const json = await res.json()
				const products = json["hydra:member"] as Product[]
				products.forEach(product => {
					this.products.push({
						picture: `/image/product/${product.picture}`,
						id: product.id,
						name: product.name,
						price: product.price
					})
				})
			} catch (err) {}
		},
		increment() {
			this.counter += 1
		}
	}
})

export default useProductStore
