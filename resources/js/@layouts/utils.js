export const openGroups = ref([])

/**
 * Return nav link props to use
 * @param {Object, String} item navigation routeName or route Object provided in navigation data
 */
export const getComputedNavLinkToProp = computed(() => link => {
    const props = {
        target: link.target,
        rel: link.rel,
        href: '',
        to: '',
    };
    // If route is string => it assumes string is route name => Create route object from route name
    // If route is not string => It assumes it's route object => returns passed route object
    if (!link.to) {
        props.to = link.to;
        return props;
    }

    if (typeof link.to === 'string') {
        props.href = route(link.to, {
            type: link.type,
            section: link.section,
            subsection: link.subsection,
            file: link.file,
        });
    } else {
        props.href = link.to;
    }

    return props;
});

/**
 * Check if nav-link is active
 * @param {Object} link nav-link object
 */
export const isNavLinkActive = (child, currentUrl) => {
    const page = usePage();
    if (child.to === null) {
        return false;
    }

    if (child.section && child.subsection && child.file) {
        return page.props.ziggy.location === page.props.ziggy.url + '/' + child.to + '/' + child.section + '/' + child.subsection + '/' + child.file;
    } else if (child.section && child.subsection) {
        return page.props.ziggy.location === page.props.ziggy.url + '/' + child.to + '/' + child.section + '/' + child.subsection;
    } else if (child.section) {
        return page.props.ziggy.location === page.props.ziggy.url + '/' + child.to + '/' + child.section;
    } else {
        return page.props.routeName === child.to;
    }
}

/**
 * Check if nav group is active
 * @param {Array} children Group children
 */
// export const isNavGroupActive = (children, router) => children.some(child => {
//   // If child have children => It's group => Go deeper(recursive)
//   if ('children' in child)
//     return isNavGroupActive(child.children, router)

//   // else it's link => Check for matched Route
//   return isNavLinkActive(child, router)
// })
export const isNavGroupActive = (children, currentUrl) => children.some(child => {
    // If child have children => It's group => Go deeper(recursive)
    if ('children' in child) {
        return isNavGroupActive(child.children, currentUrl);
    }

    // else it's link => Check for matched Route
    return isNavLinkActive(child, currentUrl);
})

/**
 * Convert Hex color to rgb
 * @param hex
 */
export const hexToRgb = hex => {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    const shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;

    hex = hex.replace(shorthandRegex, (m, r, g, b) => {
        return r + r + g + g + b + b;
    });

    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

    return result ? `${parseInt(result[1], 16)},${parseInt(result[2], 16)},${parseInt(result[3], 16)}` : null;
}
