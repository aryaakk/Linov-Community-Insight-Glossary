<template>
    <div class="container">
        <div class="row mt-4" ref="threads">
            <div class="col-lg-4 col-md-12">
                <div class="panel-subhead mt-4">
                    <div class="icon-subhead">
                        <img src="/img/icon/ic_user.png">
                    </div>
                    <div class="title-subhead">
                        <label>Profile User</label>
                    </div>
                </div>
                <div class="card row p-5 mt-4">
                    <div class="col-md-6 col-lg-12">
                    <div class="d-flex justify-content-center mt-4">
                         <img class="img-circle" style="width: 150px;height: 150px;border: 1px solid #8E8E8E; object-fit: cover;" :src="`${ profile?.photo ? profile.photo_url : '/img/home/user.png'}`" alt="">
                    </div>

                    <h4 class="text-center">{{ profile?.name }}  <img src="/img/icon/ic_verified.svg" height="20" v-if="is_consultant"></h4>
                    <p class="secondary-text pt-0">{{ profile?.position_id ?  profile?.position_id?.name : profile?.other_position }}</p>
                    <div class="d-flex justify-content-center px-5" v-if="profile">
                        <a target="_blank" rel="nofollow" :href="social.url" v-for="(social, idx) in profile?.socials||[]" :key="'social'+idx">
                            <img class="social-img" :src="social.icon" :alt="social.name">
                        </a>
                    </div>
                    </div>
                    <div class="col-md-6 col-lg-12">
                    <div class="mt-5">
                        <hr style="border: 1px solid #F0F0F0;">
                         <p><b><i class="fas fa-user mr-1"></i> Personal Details</b></p>
                        <table>
                            <tbody>
                            <tr>
                                <td class="secondary-text-small pt-3 pb-2">Email</td>
                            </tr>
                            <tr>
                                <td><b>{{ profile?.email }}</b></td>
                            </tr>
                            <tr>
                                <td class="secondary-text-small pt-3 pb-2">No. Telephone</td>
                            </tr>
                            <tr>
                                <td><b>{{ profile?.phone || '-'}}</b></td>
                            </tr>
                            <tr>
                                <td class="secondary-text-small pt-3 pb-2">Domicile</td>
                            </tr>
                            <tr>
                                <td><b>{{ profile?.city_id && profile?.state_id ? `${profile.city_id.name}, ${profile.state_id.name}` : '-'}}</b></td>
                            </tr>
                            <tr>
                                <td class="secondary-text-small pt-3 pb-2">Age</td>
                            </tr>
                            <tr>
                                <td><b>{{  profile?.birthdate ? $dayjs().diff(profile?.birthdate, 'year') : '-' }}</b></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-5 text-center">
                        <hr style="border: 1px solid #F0F0F0;">
                        <NuxtLink class="btn btn-primary btn-block" to="/profile/edit" style="color: white ;" v-if="is_current"><i class="fas fa-pencil-alt"></i> Edit Profile</NuxtLink>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="panel-subhead mt-4">
                    <div class="icon-subhead">
                        <img src="/img/icon/ic_lists.png">
                    </div>
                    <div class="title-subhead">
                        <label>Activities</label>
                    </div>
                </div>
                <div class="card mt-4 p-0">
                    <ul class="nav nav-tabs d-flex" style="border-bottom: 0px;flex-wrap: wrap;">
                        <li class="text-center" style="flex: 1;"  :class="(tab==null?'active tab-active' : '')" @click="changeTab(null)">
                            <a href="#">Detail Profile</a>
                        </li>
                        <li class="text-center dropdown" style="flex: 1;" :class="(tab!=null?'active tab-active' : '')">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Activity {{ tab ? `[${tab}]` : '' }} <span class="caret"></span></a>
                            <ul class="dropdown-menu drop-activity">
                                <li><a href="#" @click="changeTab('Thread')">Thread</a></li>
                                <li><a href="#" @click="changeTab('Consultations')">Consultations</a></li>
                                <li><a href="#" @click="changeTab('Comments')">Comments</a></li>
                                <li><a href="#" @click="changeTab('Likes')">Likes</a></li>
                                <li><a href="#" @click="changeTab('Bookmark')">Bookmark</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- <div class="card mt-4 p-0">
                    <ul class="nav nav-tabs d-flex justify-content-between" style="border-bottom: 0px;">
                        <li class="text-center col-md-4" :class="tab=='Thread' ? 'tab-active' : ''" @click="changeTab('Thread')"><a href="#">Thread</a></li>
                        <li class="text-center col-md-4" :class="tab=='Likes' ? 'tab-active' : ''" @click="changeTab('Likes')"><a href="#">Likes</a></li>
                        <li class="text-center col-md-4" :class="tab=='Bookmark' ? 'tab-active' : ''" @click="changeTab('Bookmark')"><a href="#">Bookmark</a></li>
                      </ul>
                </div> -->
                <template v-if="(tab==null)">
                <div class="card mt-4">
                    <div class="py-2">
                        <h5><b>About Me</b></h5>
                        <hr>
                        <p v-if="profile?.about_me">{{ profile?.about_me }}</p>
                        <p v-else>You have't filled out this section, please fill it <NuxtLink to="/profile/edit">here</NuxtLink></p>
                    </div>
                    <div class="py-2">
                        <h5><b>Experience</b></h5>
                        <hr>
                        <ul class="list-group" v-if="profile?.experiences && profile?.experiences.length>0">
                            <li class="list-group-item d-flex justify-content-between" v-for="(exp, idx) in profile?.experiences||[]" :key="idx">
                                <div>
                                    <h4><b>{{ exp.company}}</b></h4>
                                    <span><b>{{ exp.position}}</b></span><br>
                                    <small>{{ $dayjs(exp.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(exp.end_date).format('MMM-YYYY') }}</small>
                                </div>
                            </li>
                        </ul>
                        <p v-else>
                            You have't filled out this section, please fill it <NuxtLink to="/profile/edit-experience">here</NuxtLink>
                        </p>
                    </div>
                    <div class="py-2">
                        <h5><b>Education</b></h5>
                        <hr>
                        <ul class="list-group" v-if="profile?.educations && profile?.educations.length>0">
                            <li class="list-group-item d-flex justify-content-between" v-for="(edu, idx) in profile?.educations||[]" :key="idx">
                            <div>
                                <h5 style="font-weight: 600;">{{ edu.university_id?.name || edu.university_name || edu.other_university }}</h5>
                                <span>{{  edu.major_id?.name || edu.major_name || edu.other_major }} - {{  edu.title_degree_id?.degree || edu.degree_name || edu.other_title }}</span><br>
                                <small>{{ $dayjs(edu.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(edu.end_date).format('MMM-YYYY') }}</small>
                            </div>
                        </li>
                        </ul>
                        <p v-else>
                            You have't filled out this section, please fill it <NuxtLink to="/profile/edit-education">here</NuxtLink>
                        </p>
                    </div>
                    <div class="py-2">
                        <h5><b>Skills</b></h5>
                        <hr>
                        <div class="p-4" v-if="profile?.skills && profile?.skills.length>0">
                            <span class="px-1" v-for="(skill, idx) in profile?.skills||[]" :key="idx">
                                <span class="badge bg-secondary" style="font-size: 15px;">
                                {{ skill }} <span class="px-1"></span>
                                </span>
                            </span>
                        </div>
                        <p v-else>
                            You have't filled out this section, please fill it <NuxtLink to="/profile/edit-skills">here</NuxtLink>
                        </p>
                    </div>
                    <div class="py-2">
                        <h5><b>Certification & License</b></h5>
                        <hr>
                        <ul class="list-group" v-if="profile?.certificates && profile?.certificates.length>0">
                            <li class="list-group-item d-flex justify-content-between" v-for="(cert, idx) in profile.certificates" :key="idx">
                            <div>
                                <h5>{{ cert.title }} - {{ cert.organization }}</h5>
                                <small>{{ $dayjs(cert.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(cert.end_date).format('MMM-YYYY') }}</small>
                            </div>
                        </li>
                        </ul>
                        <p v-else>
                            You have't filled out this section, please fill it <NuxtLink to="/profile/edit-certificate">here</NuxtLink>
                        </p>
                    </div>
                </div>
                </template>
                <template v-if="threads.length>0">
                <panel-thread v-for="(thread, idx) in threads||[]" :key="idx" :data="thread" :edit="true"></panel-thread>
                <div v-show="loading"  class="d-flex justify-content-center mt-4 mb-4">
                    <img src="/img/loader.gif" style="height: 30px;margin-right: 5px;" alt="loading...">
                    <h5>Loading more awesome threads...</h5>
                </div>
                </template>
            </div>
        </div>
    </div>
</template>

<style scoped>
.img-rounded {
    border-radius: 50%;
}
.tab-active{
    background: #FCFCFC;
    font-weight: bold;
    border-bottom: 2.5px solid #62B6EE;
}
.tab-active a{
    color: #7D7D7D !important;
}
.card .nav-tabs a{
    color: #CDCDCD;
    width: 100%;
    border: 0px solid transparent !important;
}
.card .nav-tabs a:hover{
    font-weight: bold;
    color: #7D7D7D !important;
}

.drop-activity{
    width: 100%;
}

.drop-activity li a{
    color: #323232;
    padding-top: 8px;
    padding-bottom: 8px;
}
</style>
<script>
export default {
  head: {
    title: "Profile User",
  },
  layout: "default",
  middleware: ['authenticated', 'hasprofile'],
  data() {
        return {
            threads: [],
            profile: null,
            loading: false,
            stopInfinite: false,
            tab: null,
            id: this.$router.currentRoute.params.id,
            params: {
                page: 1,
            }
        }
  },
  computed:{
    is_current(){
        return this.profile?.user_id!='undefined' && this.profile?.user_id==this.$auth.user.id
    },
    is_consultant(){
        return this.profile?.is_consultant=='1'
    }
  },
  async mounted(){
    window.addEventListener("scroll", this.handleScroll)
    this.getThreads();
    await this.$getProfile({}, this.id)
            .then(response=>{
                this.profile = response.data
            })
            .catch(error=>{
                 return this.$nuxt.error({ statusCode: error.response.status, message:  error.response.statusText })
            });
   
        const user = {...this.$auth.user}
        user.avatar = this.profile?.photo ? this.profile?.photo_url : this.$auth.user.avatar;
        this.$auth.setUser(user)      
  },
  unmounted() {
    window.removeEventListener("scroll", this.handleScroll)
  },
  methods: {
    async getThreads(){
        let data = [];
        this.loading = true
        switch (this.tab) {
            case 'Thread':
                await this.$getUserThreads({params: this.params}).then((response)=>{
                    data = response.data
                })
                break;
            case 'Likes':
                await this.$getLikedThreads({params: this.params}).then((response)=>{
                    data = response.data
                })
                break;
            case 'Bookmark':
                await this.$getBookmarkedThreads({params: this.params}).then((response)=>{
                    data = response.data
                })   
                break;
            case 'Consultations':
                await this.$getConsultThreads({params: this.params}).then((response)=>{
                    data = response.data
                })
                break;
            case 'Comments':
                await this.$getCommentThreads({params: this.params}).then((response)=>{
                    data = response.data
                })
                break;
        }

        this.threads = [...this.threads,...data?.data||[]]
        this.params.page += 1;
        this.loading = false
        this.stopInfinite = this.threads.length >= data.total
    },
    handleScroll() {
      if (!this.stopInfinite && !this.loading && this.$refs.threads && Math.floor(this.$refs.threads.getBoundingClientRect().bottom) <= window.innerHeight) {
        this.getThreads();
      }
    },
    changeTab(tab){
        this.tab = tab
        this.threads = []
        this.params.page = 1
        this.getThreads()
    },
   }
}
</script>