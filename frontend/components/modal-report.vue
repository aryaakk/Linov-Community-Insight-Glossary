<template>
    <diV>
    <div id="modal-report" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-delete-thread" role="document" style="widows: 400px">
            <div class="modal-content border-modal">
            <div class="modal-header">
                <h4>Why you want report this thread? </h4>
            </div>
          <div class="modal-body" style="text-align: center; padding:15px 0 10px 0px;">
            <div class="form-group text-left">
                <label class="radio-container">Its really annoy me
                    <input type="radio" v-model="form.level" name="level" value="1">
                    <span class="checkmark"></span>
                </label>
                <label class="radio-container">Its annoy me
                    <input type="radio" v-model="form.level" name="level" value="2">
                    <span class="checkmark"></span>
                </label>
                <label class="radio-container">Its lil bit annoy me
                    <input type="radio" v-model="form.level" name="level" value="3">
                    <span class="checkmark"></span>
                </label>
                <label class="radio-container">Somehow its lil bit annoy me
                    <input type="radio" v-model="form.level" name="level" value="4">
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="form-group mt-4">
                <p class="msg-report">Reason</p>
                <textarea v-model="form.reason" name="txtReasonReport" cols="10" rows="3" class="form-control"></textarea>
            </div>
          </div>

            <div class="modal-footer border-footer">
                <button type="button" style="color: #45A1EF; margin-right: 8px;" class="btn btn-secondary button-footer-delete" ref="btnclose" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary button-footer-delete"  @click="$emit('OnReport', form);$refs.btnclose.click()">Report</button>
            </div>
            
            </div>
        </div>
    </div>
    </div>
</template>
<script>
    export default {
        name: 'modal-report',
        data() {
            return {
                form: {
                    level: 0,
                    reason: null,
                }
            }
        },
    }
</script>