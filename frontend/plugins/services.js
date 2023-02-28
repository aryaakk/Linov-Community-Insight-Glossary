import authService from '~/services/auth'
import positionService from '~/services/positions'
import industriesService from '~/services/industries'
import threadService from '~/services/thread'
import consultationService from '~/services/consultation'
import profileService from '~/services/profile'
import regionService from '~/services/region'
import socialService from '~/services/socials'
import articleService from '~/services/article'
import trainingService from '~/services/training'
import notificationService from '~/services/notification'
import consultantService from '~/services/consultant'
import glossaryService from '~/services/glossary'

export default ({ $axios }, inject) => {
    const allMethods = {
      ...authService($axios),
      ...positionService($axios),
      ...industriesService($axios),
      ...threadService($axios),
      ...consultationService($axios),
      ...profileService($axios),
      ...regionService($axios),
      ...socialService($axios),
      ...articleService($axios),
      ...trainingService($axios),
      ...notificationService($axios),
      ...consultantService($axios),
      ...glossaryService($axios)
    }
    const methods = Object.keys(allMethods)
    methods.forEach((method) => {
      inject(method, allMethods[method])
    })
}