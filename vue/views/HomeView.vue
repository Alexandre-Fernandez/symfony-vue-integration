<template>
	<section>
		<h2>Products</h2>
		<div>
			<ProductCard
				v-for="product in products"
				:key="product.name"
				:picture="product.picture"
				:name="product.name"
				:description="product.description"
				:price="product.price"
			/>
		</div>
	</section>
	<section>
		<h2>Testing</h2>
		<p>{{ counter }}</p>
		<button @click="increment">increment</button>
	</section>
</template>

<script lang="ts">
import { storeToRefs } from "pinia"
import { defineComponent } from "vue"
import ProductCard from "../components/ProductCard.vue"
import useProductStore from "../stores/product"

export default defineComponent({
	components: {
		ProductCard
	},
	setup() {
		const productStore = useProductStore()
		const { products, counter } = storeToRefs(productStore)

		return {
			products,
			counter,
			increment: productStore.increment
		}
	}
})
</script>
