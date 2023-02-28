import IndustriesService from '~/services/industries'
import PositionsService from '~/services/positions'
import UniversityService from '~/services/university'
import DegreesService from '~/services/degrees'
import MajorsService from '~/services/majors'
import RegionService from '~/services/region'
import StateService from '~/services/state'
import CityService from '~/services/city'
import CatConsultantService from '~/services/category-consultant'
import SocialMediaService from '~/services/social-media'
import BankService from '~/services/banks'
import TrainerService from '~/services/trainers'
import ProviderService from '~/services/providers'
import TrainEventService from '~/services/trainevents'
import CategoryService from '~/services/category'
import OrderService from '~/services/orders'
import InsightService from '~/services/insight'
import SyllabusService from '~/services/syllabus'
import SkillService from '~/services/skills'
import ConsultantService from '~/services/consultant'
import ThreadService from '~/services/thread'

export default ({ $axios }, inject) => {
    const allMethods = {
      ...IndustriesService($axios),
      ...PositionsService($axios),
      ...UniversityService($axios),
      ...DegreesService($axios),
      ...MajorsService($axios),
      ...RegionService($axios),
      ...StateService($axios),
      ...CityService($axios),
      ...CatConsultantService($axios),
      ...SocialMediaService($axios),
      ...BankService($axios),
      ...TrainerService($axios),
      ...ProviderService($axios),
      ...TrainEventService($axios),
      ...CategoryService($axios),
      ...OrderService($axios),
      ...InsightService($axios),
      ...SyllabusService($axios),
      ...SkillService($axios),
      ...ConsultantService($axios),
      ...ThreadService($axios)
    }
    const methods = Object.keys(allMethods)
    methods.forEach((method) => {
      inject(method, allMethods[method])
    })
}