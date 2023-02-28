<template>
    <div class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ title }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="$toggleModal(modal.$el)"></button>
            </div>
            <div class="modal-body">
                <slot/>
            </div>
            <div class="modal-footer">
                <button type="button" @click="$toggleModal(modal.$el)" class="btn btn-danger">Tidak</button>
                <button type="button" @click="$toggleModal(modal.$el);$emit('confirm', true)" class="btn btn-primary">Ya</button>
            </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'confirm-basic',
        props: ['title', 'modal'],
    }
</script>