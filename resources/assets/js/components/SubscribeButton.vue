<template>
	<div :class='classes' @click='subscribe'>Subscribe</div>
</template>

<script>
import axios from 'axios'

export default {
	props: ['subscribed'],
	methods: {
		subscribe(){
			let requestType = this.subscribed ? 'delete' : 'post'

			axios[requestType](`${location.pathname}/subscriptions`)
				.then((res)=>{
					console.log(res);
					this.subscribed = !this.subscribed;
				})
		}
	},
	computed: {
		classes(){
			return ['btn', this.subscribed ? "btn-primary" : "btn-default"]
		}
	}
}

</script>