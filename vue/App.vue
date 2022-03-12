<template>
	<Header />
	<main>
		<router-view />
	</main>
</template>

<script lang="ts">
import { defineComponent } from "vue"
import Header from "./components/Header.vue"

export default defineComponent({
	components: {
		Header
	}
})
</script>
