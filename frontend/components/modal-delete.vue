<template>
    <div>
        <div id="modal-delete" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-delete-thread" role="document" style="widows: 400px">
                <div class="modal-content border-modal">
                <div class="modal-header header-delete">
                    Delete This {{ context }}?
                </div>
                <div class="modal-body" style="text-align: center;">
                    <p>
                        Are you sure? You will not able to recover this this  {{ context }}!
                    </p>
                </div>

                <div class="modal-footer border-footer">
                    <button type="button"  @click="$emit('onClicked', false)" ref="btnclose" style="color: #45A1EF;" class="btn btn-secondary button-footer-delete" data-dismiss="modal">Close</button>
                    <button type="button" @click="$emit('onClicked', true);$refs.btnclose.click()" class="btn btn-primary button-footer-delete">Delete</button>
                </div>
                
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'modal-delete',
        props: ['context'],
        methods: {
        }
    }
</script>