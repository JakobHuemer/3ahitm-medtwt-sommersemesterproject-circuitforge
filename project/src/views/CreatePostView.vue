<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCloudArrowUp, faImage, faPlus } from '@fortawesome/free-solid-svg-icons'
import { ref } from 'vue'
import TagsContainer from '@/components/TagsContainer.vue'
import ButtonComponent from '@/components/ButtonComponent.vue'

const hashtags = ref<string[]>(['hello', 'thisIsTag', 'memo'])
const versions = ref<string[]>(['1.21+', '1.8.9'])
const downloadables = ref<string[]>(['world.zip', 'door.litematic', 'door.schematic'])

// function setFromJson() {
//     console.log('Setting json', outputJson.value)
//
//     var json = JSON.parse(outputJson.value)
//
//     componentRef.value?.setJson(json)
// }
</script>

<template>
    <div class="container-wrapper">
        <h2>Create Post</h2>
        <div class="container container-images">
            <div class="scroll-container">
                <div class="post-image add-image-button">
                    <FontAwesomeIcon :icon="faCloudArrowUp" />
                    <span>Add Image</span>
                </div>
                <div class="post-image">
                    <img
                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.photographylife.com%2Fwp-content%2Fuploads%2F2014%2F06%2FNikon-D810-Image-Sample-6.jpg&f=1&nofb=1&ipt=7725af85bf7241ed3992926d1f0b192b48bebee7488f69025b9f125661665464"
                        alt="asdsad"
                    />
                </div>
                <div class="post-image">
                    <img
                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.photographylife.com%2Fwp-content%2Fuploads%2F2014%2F06%2FNikon-D810-Image-Sample-6.jpg&f=1&nofb=1&ipt=7725af85bf7241ed3992926d1f0b192b48bebee7488f69025b9f125661665464"
                        alt="asdsad"
                    />
                </div>
                <div class="post-image">
                    <img
                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.photographylife.com%2Fwp-content%2Fuploads%2F2014%2F06%2FNikon-D810-Image-Sample-6.jpg&f=1&nofb=1&ipt=7725af85bf7241ed3992926d1f0b192b48bebee7488f69025b9f125661665464"
                        alt="asdsad"
                    />
                </div>
                <div class="post-image">
                    <img
                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.photographylife.com%2Fwp-content%2Fuploads%2F2014%2F06%2FNikon-D810-Image-Sample-6.jpg&f=1&nofb=1&ipt=7725af85bf7241ed3992926d1f0b192b48bebee7488f69025b9f125661665464"
                        alt="asdsad"
                    />
                </div>
                <div class="post-image">
                    <img
                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.photographylife.com%2Fwp-content%2Fuploads%2F2014%2F06%2FNikon-D810-Image-Sample-6.jpg&f=1&nofb=1&ipt=7725af85bf7241ed3992926d1f0b192b48bebee7488f69025b9f125661665464"
                        alt="asdsad"
                    />
                </div>
                <div class="post-image">
                    <img
                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.photographylife.com%2Fwp-content%2Fuploads%2F2014%2F06%2FNikon-D810-Image-Sample-6.jpg&f=1&nofb=1&ipt=7725af85bf7241ed3992926d1f0b192b48bebee7488f69025b9f125661665464"
                        alt="asdsad"
                    />
                </div>
                <div class="post-image">
                    <img
                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.photographylife.com%2Fwp-content%2Fuploads%2F2014%2F06%2FNikon-D810-Image-Sample-6.jpg&f=1&nofb=1&ipt=7725af85bf7241ed3992926d1f0b192b48bebee7488f69025b9f125661665464"
                        alt="asdsad"
                    />
                </div>
            </div>
        </div>
        <div class="container container-post">
            <input type="text" name="title" id="title" class="input-title" />

            <TagsContainer :versions="versions" :hashtags="hashtags" />

            <div class="editor-wrapper">COMMING</div>

            <div class="downloadables">
                <div class="download" v-for="asset of downloadables">
                    <div class="thumbnail">
                        <img src="../assets/img/icons/shulker-white.png" alt="download" />
                    </div>
                    <span>{{ asset }}</span>
                </div>
                <ButtonComponent :icon="faPlus">Add File</ButtonComponent>
            </div>
        </div>
    </div>
</template>

<style scoped>
h2 {
    text-align: center;
    font-family: var(--font-title);
    font-size: var(--font-size-body);
}

.container {
    border-radius: var(--border-radius);
}

.container-wrapper {
    display: flex;
    flex-direction: column;
    gap: var(--gap-16);
    position: relative;
}

/* Custom Horizontal Scrollbar Styling for .container-images */

/* Target only .container-images for Firefox */
.container-images {
    scrollbar-width: thin;
    scrollbar-color: rgba(155, 155, 155, 0.5) transparent;
    overflow-x: auto; /* Enable horizontal scrolling */
    overflow-y: hidden; /* Prevent vertical scrolling */
    white-space: nowrap; /* Prevent content from wrapping */
    padding-bottom: 3px; /* Add padding equal to scrollbar height */
    margin-bottom: -3px; /* Pull it back up to make scrollbar not take space */
}

/* WebKit browsers (Chrome, Safari, newer Edge) */
.container-images::-webkit-scrollbar {
    height: 3px; /* Horizontal scrollbar height */
    width: 3px; /* Also setting width for vertical scrollbar for consistency */
}

.container-images::-webkit-scrollbar-track {
    background: transparent; /* Track background */
    border-radius: 10px;
}

.container-images::-webkit-scrollbar-thumb {
    background: rgba(155, 155, 155, 0.5); /* Scrollbar color */
    border-radius: 10px; /* Rounded corners */
}

.container-images::-webkit-scrollbar-thumb:hover {
    background: rgba(155, 155, 155, 0.8); /* Darker on hover */
}

/* For Internet Explorer and older Edge */
.container-images {
    -ms-overflow-style: none; /* IE and Edge - hide default scrollbar */
}

.container-images {
    --container-images-gap: var(--gap-16);
    width: 100%;
    position: relative;
    padding: var(--container-images-gap) 0;
    overflow: hidden;

    &::before,
    &::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: calc(var(--container-images-gap) * 2);
        z-index: 100;
        /*backdrop-filter: blur(20px);*/
        background: var(--col-container);
        mask-image: linear-gradient(to right, white, transparent);
    }

    .scroll-container {
        overflow-x: scroll;
        overflow-y: hidden;

        width: auto;
        gap: var(--gap-8);
        display: flex;

        .post-image {
            display: inline;
            height: 10rem;
            aspect-ratio: 1;
            border-radius: var(--border-radius-s);

            &:first-child {
                margin-left: var(--container-images-gap);
            }

            &:last-child {
                margin-right: var(--container-images-gap);
            }

            img {
                border-radius: var(--border-radius-s);
                height: 100%;
                width: 100%;
                object-fit: cover;
            }
        }

        .add-image-button {
            gap: var(--gap-8);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background: var(--col-surface);

            user-select: none;
            -moz-user-select: none;
            -webkit-user-select: none;

            cursor: pointer;

            transition: background-color 0.2s;

            &:hover {
                background: var(--col-surface-hover);
            }

            &:active {
                background: var(--col-surface-active);
            }

            svg {
                height: 20%;
            }
        }
    }
}

.container-post {
    padding: var(--gap-16);
}
</style>
