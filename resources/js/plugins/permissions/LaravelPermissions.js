import { can, is } from '../../utils/auth.js'

export default {
	install(app) {
        app.config.globalProperties.can = function(value) {
            const user = this.$page.props.auth.user;
            return can(user, value);
        }

        app.config.globalProperties.is = function(value) {
            const user = this.$page.props.auth.user;
            return is(user, value);
        }
    }
}
