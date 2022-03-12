<template>
	<article>
		<img :src="picture" />
		<h3>{{ name }}</h3>
		<p>{{ description }}</p>
		<strong>
			<span>{{ price }}</span> €
		</strong>
	</article>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
	props: {
		name: String,
		description: String,
		picture: String,
		price: Number
	},
	setup({ name, description, picture, price }) {
		return {
			name,
			description,
			price,
			picture
		}
	}
})
</script>

<style scoped>
article {
	width: 15%;
	text-align: center;
	background-color: #e9e9e9;
	padding: 1rem;
	border-radius: 5px;
}
article img {
	width: 100%;
}
article h3 {
	margin: 0;
}
</style>
