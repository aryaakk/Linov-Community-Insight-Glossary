<template>
    <div class="panel-subhead">
        <modal-delete v-if="showModalDelete" @onClicked="handleAction" context="Comment"></modal-delete>
        <div class="main-thread">
            <div class="d-flex head-comment">
                <img :src="comment.author?.photo || '/img/home/user.png'" class="img-circle mr-4 avatar-nav cursor" alt="Avatar">
                <label class="lblAvaThread mt-2">{{ comment.author?.name }}</label>
                <img src="/img/icon/ic_verified.svg" style="height: 20px !important; margin-top: 8px;" v-if="comment.author.badge=='1'">
            </div>
            <div class="mt-4" style="min-height: 50px">
                {{ comment.comment }}
            </div>
            <div class="d-flex justify-content-between pt-2 mt-4" style=" border-top: 1px solid #E6E6E6;">
                <div class="pt-2">
                    <span class="cursor" style="color: #BEBDBD">{{ comment.human_date }}</span>
                </div>
                <div class="pt-2 d-flex justify-content-right">
                    <div class="dropup " v-if="$auth.loggedIn && $auth.user.id==comment.user_id">
                        <div class="btn-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </div>
                        <ul class="dropdown-menu postion-dropdown p-3">
                            <!-- <li class="dropdown-item mt-2" style="cursor: pointer;"><i class="fas fa-pencil-alt opt-color"></i> Edit</li> -->
                            <li @click="showModalDelete=true" data-toggle="modal" data-target="#modal-delete" class="dropdown-item mt-2 opt-delete" style="cursor: pointer;"><i class="fas fa-trash-alt"></i> Delete</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
  name: "panel-comment-insight",
  props: ["comment"],
  data() {
    return {
        form:{
            comment:'',
            mode: 'comment',
            processing: false,
            errors: [],
        },
        showModalDelete: false,
    }
  },
  methods: {
    async handleAction(status){
        if(status){
            if (!this.$auth.loggedIn) {
                this.$router.push("/auth/login");
                return;
            }
            this.$emit('onDelete', this.comment.id);
        }
        this.showModalDelete = false;
    },
  }
}
</script>