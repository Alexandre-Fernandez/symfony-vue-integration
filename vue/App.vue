<template>
	<Header />
	<main>
		<router-view />
	</main>
</template>

<script lang="ts">
import { defineComponent } from "vue"
import Header from "./components/Header.vue"
import useProductStore from "./stores/product"

export default defineComponent({
	components: {
		Header
	},
	setup() {
		useProductStore().init()
	}
})
</script>
