import type { Ref } from "vue"
import { ref, onUnmounted } from "vue"

const useFetch = <D = any>(useCache = false, cacheExpiration = 43200000) => {
	const data: Ref<null | Data<D>> = ref(null)
	const error: Ref<null | Error> = ref(null)
	const isLoading = ref(true)
	let isMounted = true

	onUnmounted(() => (isMounted = false))

	const load = async (
		url: string,
		method: HttpMethod = "GET",
		{ jwt, body }: LoadOptions = {}
	) => {
		data.value = null
		error.value = null
		isLoading.value = true
		if (useCache) {
			const cached = localStorage.getItem(url) as unknown as CachedData<D>
			if (cached && Date.now() < cached.expiration) {
				data.value = { content: cached.data }
				isLoading.value = false
				return
			}
		}
		let errorCode = 500
		try {
			const res = await fetch(url, {
				method,
				headers: createHeaders(jwt, body?.contentType),
				body: method === "GET" && body ? createBody(body) : undefined
			})
			if (!res.ok) {
				errorCode = res.status
				throw res.statusText
			}
			if (!isMounted) return
			const text = await res.text()
			if (!text) data.value = { content: null }
			else {
				const content = (getJson(text) ?? text) as D
				if (useCache) {
					localStorage.setItem(
						url,
						JSON.stringify({
							data: content,
							expiration: Date.now() + cacheExpiration
						} as CachedData<D>)
					)
				}
				data.value = { content }
			}
		} catch (err) {
			if (!isMounted) return
			error.value = { code: errorCode, content: String(err) }
		} finally {
			isLoading.value = false
		}
	}

	return { data, error, isLoading, load }
}

export default useFetch

// ANCHOR Helpers

function createHeaders(jwt?: Jwt, contentType?: ContentType) {
	const headers = new Headers()
	if (jwt) headers.set("Authorization", `Bearer ${jwt}`)
	// setting multipart/form-data breaks the request (for some reason) :
	if (contentType && contentType !== "multipart/form-data")
		headers.set("Content-Type", contentType)
	return headers
}

function createBody({ content, contentType }: Body) {
	let body = undefined
	switch (contentType) {
		case "application/json":
			body = JSON.stringify(content)
			break
		case "multipart/form-data":
			const formData = new FormData()
			for (const key in content) {
				formData.set(key, String(content[key]))
			}
			body = formData
			break
	}
	return body
}

function getJson(string: string) {
	try {
		return JSON.parse(string)
	} catch (err) {
		return null
	}
}

// ANCHOR Types

interface LoadOptions {
	jwt?: Jwt
	body?: Body
}
type CachedData<D = any> = { data: D; expiration: number }
type Data<T = any> = {
	content: T | null
}
type Error = {
	code: number
	content: string
}

/* HTTP REQUEST TYPES */
type Jwt = string
type HttpMethod = "GET" | "POST" | "PUT" | "PATCH" | "DELETE"
type ContentType = "application/json" | "multipart/form-data"
type Content = { [key: string | number]: any } | any[]
type Body = {
	contentType: ContentType
	content: Content
}
