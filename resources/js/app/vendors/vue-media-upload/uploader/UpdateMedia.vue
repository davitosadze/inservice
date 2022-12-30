<template>
    <div>
        <div class="">
            <div class="gallery width-100" :class="error?'red-border':''">
                <Loader
                    color="#0275d8" 
                    :active="loading" 
                    spinner="line-scale" 
                    background-color = 'rgba(255, 255, 255, .4)'
                />
                <div class="elements-wraper">
                    
                    <!--UPLOAD BUTTON-->
                    <div class="button-container image-margin">
                        <label :for="`images-upload-${index}-${each}`" class="images-upload">
                            <svg
                                class="custum-icon"
                                xmlns="http://www.w3.org/2000/svg" 
                                width="1em" 
                                height="1em" 
                                preserveAspectRatio="xMidYMid meet" 
                                viewBox="0 0 24 24">
                                    <g fill="none">
                                        <path 
                                            d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11s11-4.925 11-11S18.075 1 12 1zm1 15a1 1 0 1 1-2 0v-3H8a1 1 0 1 1 0-2h3V8a1 1 0 1 1 2 0v3h3a1 1 0 1 1 0 2h-3v3z" 
                                            fill="currentColor"/>
                                    </g>
                            </svg>
                        </label>     
                        <input @change="fileChange" :id="`images-upload-${index}-${each}`" type="file" accept="image/*" :multiple="index !== 'inter'" hidden>
                    </div>

                    <!--IMAGES PREVIEW-->
                    
                    <div v-for="(image, i) in saved_media" :key="index" class="image-container image-margin">
                        <img :src="image.original_url.replace('localhost', '213.131.33.218:4000')" alt=""  class="images-preview">
                        <button @click="remove_saved_media(image)" class="close-btn" type="button">
                            <i style="cursor:pointer; color:red; font-size:1.3em;" class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div v-for="(image, i) in added_media" :key="index" class="image-container image-margin">
                        <img :src="image.url" alt=""  class="images-preview">
                        <button @click="remove(i)" class="close-btn" type="button">
                            <i style="cursor:pointer; color:red; font-size:1.3em;" class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if='error' id="media-required">
            <p class='red-text'>{{error}}</p>
        </div>
        <div v-for="(image, i) in added_media" :key="index" class="m-top">
            <input type="text" :name="inputName (i)" :value="image.name" hidden>
        </div>
        <div v-for="(image, i) in deleted_media" :key="index" class="m-top">
            <input type="text" :name="removeInput (i)" :value="image.id" hidden>
        </div>
        <div v-if="(added_media.length||saved_media.length)" class="m-top">
            <input type="text" name="media" value="1" hidden>
        </div>
    </div>
</template>

<script>
    import Loader from './loader/index.vue';
    import axios from 'axios'

    export default {
        
        data(){
            return{
                added_media:[],
            
                saved_media:[],
                deleted_media:[],

                loading:true
            }
        },
        computed: {
        },
        methods:{
            inputName (i) {
                return (this.index !== 'inter') ? `item[${this.index}][${this.each}][media][${i}]` : `signature`
            },
            removeInput (i) {
                return (this.index !== 'inter') ? `deleted_media[${this.index}][${i}]` : `deleted_media`
            },
            async fileChange(event){
                this.loading=true
                let files = event.target.files
                for(var i=0; i < files.length; i++){
                    let formData = new FormData
                    let url = URL.createObjectURL(files[i])
                    formData.set('image', files[i])
                    const {data} = await axios.post(this.server, formData)
                        
                    this.added_media.push({url:url, name:data.name, size:files[i].size, type:files[i].type});
                }
                this.loading=false
                this.media_emit()
            },
            remove(index){
                this.added_media.splice(index,1)
                this.media_emit()
            },
            remove_saved_media(index){
                this.deleted_media.push({...index});
                this.saved_media.splice(this.saved_media.findIndex(i => i.id == index.id),1)
                this.media_emit()
            },
            media_emit(){
                this.$emit('added-media',this.added_media)
                this.$emit('deleted-media', this.deleted_media)
                this.$emit('saved-media', this.saved_media)
            }
            
        },
        props:{
            index: { type: [String, Number] },
            each: { type: [String, Number] },
            media_server:{
                type: String,
                required : true,
            },
            error:'',
            server: {
                type: String,
                default: '/api/upload',
            }
            // media_file_path:{
            //     type: String,
            //     required : true,
            // }
        },
        mounted() {
            axios.get(this.media_server)
                .then(response=>{
                    this.saved_media=response.data.media
                    this.loading = false
                    
                    this.media_emit()
                })
                
        },
        components:{Loader}
    }
        
</script>

<style scoped>
.image-wraper{
    min-height: 200px !important;
}

.gallery{
    background-color: #fbfbfb !important;
    border-radius: 5px !important;
    border-style: solid !important;
    border: 1px solid #bbbbbb !important;
    height: 85px !important;
    line-height: 1 !important;
    box-sizing: border-box !important;
    height: auto !important;
}

.images-upload {
    background-color: #ffffff !important;
    border-radius: 5px !important;
    border: 1px dashed #ccc !important;
    display: inline-block !important;
    cursor: pointer !important;
    width: 165px !important;
    height: 88px !important;
}
.images-upload:hover{
    background-color: #f1f1f1 !important;
}
.image-container{
    display: inline-table !important;
    height: 90px !important;
    width: 140px !important;
    display: flex !important;
}
.images-preview {
    border-radius: 5px !important;
    border: 1px solid #ccc !important;
    display: inline-block !important;
    width: 140px !important;
    height: 88px !important;
    padding-top: -14px !important;
    transition: filter 0.1s linear;
    
}
.images-preview:hover{
    filter: brightness(90%);
}

.button-container{
    display: inline-flex !important;
    height: 90px !important;
    width: 140px !important;
    margin-right: 0.25rem !important;
    margin-left: 0.25rem !important;
}

.close-btn{
    background: none !important;
	color:red !important;
	border: none !important;
	padding: 0px !important;
    margin:0px !important;
	font: inherit !important;
	cursor: pointer !important;
	outline: inherit !important;
    position: relative !important;
    right: 34px !important;
    top: -27px !important;
    width: 0px !important;
}
.times-icon{
    font-size: 3rem !important;
    padding: 0px !important;
    margin:0px !important;
}
.custum-icon{
    color: #00afca !important;
    font-size: 3rem !important;
    margin-top: 18px !important;
    margin-left: 44px !important;
    
}
.custum-icon:hover{
    color: #29818f !important;
}
.close-btn:hover{
    color: rgb(190, 39, 39) !important;
}


/* -------------------- */


.width-100 {
  width: 100% !important;
}
.red-border {
    border: 1px solid #dc3545 !important;
    border-color: #dc3545 !important;
}

.elements-wraper {
  padding: 1rem !important;
  display: flex !important;
  flex-wrap: wrap !important;

}
.align-center {
  text-align: center !important;
}
.m-top-1 {
  margin-top: 0.25rem !important;
}

.image-margin {
    margin-right: 0.25rem !important;
    margin-left: 0.25rem !important;
    margin-top: 0.25rem !important;
    margin-bottom: 0.25rem !important;
}
.red-text {
    color: #d82335;
}

</style>
