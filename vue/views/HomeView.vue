<template>
	<section>
		<h2>Menus</h2>
		<div>
			<ProductCard
				picture="El-Tacon.jpg"
				name="El Tacon"
				description="Let's hope you're hungry"
				price="12.5"
			/>
		</div>
	</section>
</template>

<script lang="ts">
import { defineComponent } from "vue"
import ProductCard from "../components/ProductCard.vue"

export default defineComponent({
	components: {
		ProductCard
	}
})
</script>
