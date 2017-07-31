<script>
export default {
	props: ['attributes'],
	data(){
		return {
			editing: false,
			body: this.attributes.body 	// passed in the reply from Laravel
		};
	},
	methods: {
		update(){
			axios.patch("/replies/" + this.attributes.id, {
				body: this.body
			})

			this.editing = false;
			flash('Your reply was updated');
		}
	}
}
</script>