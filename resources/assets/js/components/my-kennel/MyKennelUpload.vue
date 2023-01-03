<script>
import Vue from "vue";
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
import ApiService from "../../services/ApiService";

export default {
  props: ["nonce", "pagetitle"],
  components: {
    vueDropzone: vue2Dropzone
  },
  data() {
    return {
      dropzoneOptions: {
        url: "/wp-json/wp/v2/ownerphotos/upload",
        thumbnailMethod: "contain",
        thumbnailWidth: 250,
        maxFilesize: 3,
        maxFiles: 1,
        autoProcessQueue: false,
        headers: { "X-WP-Nonce": this.nonce },
        dictDefaultMessage:
          "Drop files or click here to upload your dog's photo",
        acceptedFiles: "image/*"
      },
      showDropzone: true,
      state: "uploadForm",
      mediaId: 0,
      raceName: "",
      petName: "",
      isSaving: false,
      ourDogsPage: window.__GBGB_GLOBAL__.pages.ourDogs,
      errorMsg: "",
      crossIcon: window.__GBGB_GLOBAL__.icons.crossBig
    };
  },
  methods: {
    handleError(file, message, xhr) {
      this.state = "error";
      this.errorMsg = message;
      this.$refs.dropzone.removeAllFiles();
    },
    handleRemove() {
      this.$refs.dropzone.removeAllFiles();
      this.state = "uploadForm";
    },
    handleMaxFilesReached() {
      this.state = "editCaption";
    },
    handlePending() {
      this.state = "pending";
    },
    handleUploaded(response) {
      if (response.xhr.status === 200) {
        const responseObject = JSON.parse(response.xhr.response);
        this.mediaId = responseObject.imageId;
        ApiService.createOwnerPhoto(
          this.nonce,
          this.mediaId,
          this.raceName,
          this.petName
        ).then(response => {
          this.isSaving = false;
          this.showDropzone = false;
          this.state = "final";
        });
      } else {
        this.state = "error";
        this.showDropzone = false;
        this.errorMsg = "Server error, please try again later";
      }
    },
    submit() {
      if (this.isSaving) {
        return;
      }
      this.isSaving = true;
      this.$refs.dropzone.processQueue();
    }
  }
};
</script>

<template>
  <div class="MyKennel__wrapper">
    <div class="MyKennel__header">
      <mykennel-top-header :pagetitle="pagetitle"></mykennel-top-header>
    </div>

    <div class="MyKennel__inner">
      <div class="MyKennel--uploadphotos__block">
        <div class="MyKennel--uploadphotos__block__remove" v-if="state==='editCaption'">
          <span
            @click="handleRemove"
            v-html="crossIcon"
            class="Icon SvgContainer"
            aria-hidden="true"
          ></span>
        </div>
        <vue-dropzone
          @vdropzone-error="handleError"
          @vdropzone-upload-progress="handlePending"
          @vdropzone-success="handleUploaded"
          @vdropzone-max-files-reached="handleMaxFilesReached"
          ref="dropzone"
          id="dropZone"
          class="MyKennel--uploadphotos__block__dropzone"
          :options="dropzoneOptions"
          v-if="showDropzone"
        ></vue-dropzone>
        <p
          class="MyKennel--uploadphotos__block__message"
          v-if="state==='pending'"
        >Processing your file...</p>
        <p class="MyKennel--uploadphotos__block__message" v-if="state==='error'">{{ errorMsg }}</p>
        <div class="MyKennel--uploadphotos__block__addCaption" v-if="state==='editCaption'">
          <div class="MyKennel--uploadphotos__block__title">Add caption</div>
          <p class="MyKennel--uploadphotos__block__copy">This will appear next to your image</p>
          <animated-input
            v-model="raceName"
            class="MyKennel--uploadphotos__block__raceName"
            type="text"
            placeholder="Race name"
          />
          <animated-input
            v-model="petName"
            class="MyKennel--uploadphotos__block__petName"
            type="text"
            placeholder="Pet name"
          />
          <button
            type="button"
            @click="submit"
            class="MyKennel--uploadphotos__block__button MyKennelButton Button Button--primary"
            :class="{'MyKennelButton--savingState': isSaving }"
          >Done</button>
        </div>
        <div class="MyKennel--uploadphotos__block__final" v-if="state==='final'">
          <div class="MyKennel--uploadphotos__block__title">Thanks for your photo!</div>
          <div class="MyKennel--uploadphotos__block__copy">
            Our team are reviewing your photo now and it will shortly appear in the
            <a
              :href="ourDogsPage"
            >Our Dogs</a> section.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
