<template>
	<div>
		<div v-for="(reply, index) in items" :key='reply.id'>
			<reply :data='reply' @deleted='remove(index)'></reply>
		</div>

		<!-- When paginator fires an event called changed, get new data -->
		<paginator :dataSet='dataSet' @changed='fetch'></paginator>

		<new-reply @created='add'></new-reply> <!-- Runs add when created event is emitted -->
	</div>
</template>

<script>
import Reply from './Reply.vue';
import NewReply from './NewReply.vue';
import collection from '../mixins/collection';

export default {
	components: { Reply, NewReply },
	mixins: [ collection ],
	data(){
		return { dataSet: false }
	},
	created(){
		// grab replies data that we need for page
		this.fetch();
	},
	methods: {
		fetch(page){
			axios.get(this.url(page))
				.then(this.refresh);
		},
		url(page){
			if(! page){
				let query = location.search.match(/page=(\d+)/);
				page = query ? query[1] : 1;
			}
			// gets current url
			return `${location.pathname}/replies?page=${page}`;
		},
		refresh({data}){ // es6 destructuring
			this.dataSet = data;
			this.items = data.data;

			window.scrollTo(0, 0);
		}
	}
}
</script>