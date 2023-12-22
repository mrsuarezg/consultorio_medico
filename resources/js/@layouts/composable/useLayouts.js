import { AppContentLayoutNav, NavbarType } from '../enums'
import { config } from '@layouts/config'
import { injectionKeyIsVerticalNavHovered } from '@layouts'
import { computed, inject, watch } from 'vue'

export const useLayouts = () => {
  const navbarType = computed({
    get() {
      return config.navbar.type.value
    },
    set(value) {
      config.navbar.type.value = value
    },
  })

  const isNavbarBlurEnabled = computed({
    get() {
      return config.navbar.navbarBlur.value
    },
    set(value) {
      config.navbar.navbarBlur.value = value
      localStorage.setItem(`${config.app.title}-navbarBlur`, value.toString())
    },
  })

//   const _setAppDir = dir => {
//     document.documentElement.setAttribute('dir', dir)
//   }

  const footerType = computed({
    get() {
      return config.footer.type.value
    },
    set(value) {
      config.footer.type.value = value
    },
  })

  const isVerticalNavCollapsed = computed({
    get() {
      return config.verticalNav.isVerticalNavCollapsed.value
    },
    set(val) {
      config.verticalNav.isVerticalNavCollapsed.value = val
      localStorage.setItem(`${config.app.title}-isVerticalNavCollapsed`, val.toString())
    },
  })

  const appContentWidth = computed({
    get() {
      return config.app.contentWidth.value
    },
    set(val) {
      config.app.contentWidth.value = val
      localStorage.setItem(`${config.app.title}-contentWidth`, val.toString())
    },
  })

  const appContentLayoutNav = computed({
    get() {
      return config.app.contentLayoutNav.value
    },
    set(val) {
      config.app.contentLayoutNav.value = val

      // If Navbar type is hidden while switching to horizontal nav => Reset it to sticky
      if (val === AppContentLayoutNav.Horizontal) {
        if (navbarType.value === NavbarType.Hidden)
          navbarType.value = NavbarType.Sticky
        isVerticalNavCollapsed.value = false
      }
    },
  })

//   const horizontalNavType = computed({
//     get() {
//       return config.horizontalNav.type.value
//     },
//     set(value) {
//       config.horizontalNav.type.value = value
//     },
//   })

  const isLessThanOverlayNavBreakpoint = computed(() => {
    return windowWidth => unref(windowWidth) < config.app.overlayNavFromBreakpoint
  })

  const _layoutClasses = computed(() => (windowWidth, windowScrollY) => {
    return [
      `layout-nav-type-${appContentLayoutNav.value}`,
      `layout-navbar-${navbarType.value}`,
      `layout-footer-${footerType.value}`,
      {
        'layout-vertical-nav-collapsed': isVerticalNavCollapsed.value
                    && appContentLayoutNav.value === 'vertical'
                    && !isLessThanOverlayNavBreakpoint.value(windowWidth),
      },
    //   { [`horizontal-nav-${horizontalNavType.value}`]: appContentLayoutNav.value === 'horizontal' },
      `layout-content-width-${appContentWidth.value}`,
      { 'layout-overlay-nav': isLessThanOverlayNavBreakpoint.value(windowWidth) },
      { 'window-scrolled': unref(windowScrollY) },
    //   route.meta.layoutWrapperClasses ? route.meta.layoutWrapperClasses : null,
    ]
  })


//   /*
//       This function will return true if current state is mini. Mini state means vertical nav is:
//         - Collapsed
//         - Isn't hovered by mouse
//         - nav is not less than overlay breakpoint (hence, isn't overlay menu)

//       ℹ️ We are getting `isVerticalNavHovered` as param instead of via `inject` because
//           we are using this in `VerticalNav.vue` component which provide it and I guess because
//           same component is providing & injecting we are getting undefined error
//     */
  const isVerticalNavMini = (windowWidth, isVerticalNavHovered = null) => {
    const isVerticalNavHoveredLocal = isVerticalNavHovered || inject(injectionKeyIsVerticalNavHovered) || ref(false)

    return computed(() => isVerticalNavCollapsed.value && !isVerticalNavHoveredLocal.value && !isLessThanOverlayNavBreakpoint.value(unref(windowWidth)))
  }

  const dynamicI18nProps = computed(() => (key, tag = 'span') => {
    if (config.app.enableI18n) {
      return {
        keypath: key,
        tag,
        scope: 'global',
      }
    }

    return {}
  })

//   const isAppRtl = computed({
//     get() {
//       return config.app.isRtl.value
//     },
//     set(value) {
//       config.app.isRtl.value = value
//       localStorage.setItem(`${config.app.title}-isRtl`, value.toString())
//       _setAppDir(value ? 'rtl' : 'ltr')
//     },
//   })

  return {
    navbarType,
    isNavbarBlurEnabled,
    footerType,
    isVerticalNavCollapsed,
    appContentWidth,
    appContentLayoutNav,
    // horizontalNavType,
    isLessThanOverlayNavBreakpoint,
    _layoutClasses,
    isVerticalNavMini,
    dynamicI18nProps,
    // isAppRtl,
    // _setAppDir,
  }
}
