const VARIANTS = {
    default: (width, height, chamfer) =>
        `0,0 ${width - chamfer},0 ${width},${chamfer} ${width},${height} ${chamfer},${height} 0,${height - chamfer}`,
    'top-right': (width, height, chamfer) =>
        `0,0 ${width - chamfer},0 ${width},${chamfer} ${width},${height} 0,${height}`,
    'bottom-corners': (width, height, chamfer) =>
        `0,0 ${width},0 ${width},${height - chamfer} ${width - chamfer},${height} ${chamfer},${height} 0,${height - chamfer}`,
}

function parseChamferSize(value) {
    const size = parseFloat(value)

    return Number.isFinite(size) ? size : 8
}

function resolveVariant(element) {
    if (element.classList.contains('chat-input-wrap')) {
        return 'top-right'
    }

    if (element.classList.contains('retro-podium-block')) {
        return 'bottom-corners'
    }

    return 'default'
}

function updateChamferBorder(element) {
    const svg = element.querySelector('.retro-chamfer__border')
    const fill = svg?.querySelector('[data-chamfer-fill]')
    const stroke = svg?.querySelector('[data-chamfer-stroke]')

    if (!fill || !stroke) {
        return
    }

    const styles = getComputedStyle(element)
    const chamfer = parseChamferSize(styles.getPropertyValue('--rc'))
    const fillColor = styles.getPropertyValue('--rc-fill').trim()
    const width = element.clientWidth
    const height = element.clientHeight

    if (width <= 0 || height <= 0) {
        return
    }

    const cut = Math.min(chamfer, width / 2, height / 2)
    const variant = resolveVariant(element)
    const points = VARIANTS[variant](width, height, cut)

    fill.setAttribute('points', points)
    stroke.setAttribute('points', points)
    fill.setAttribute('fill', fillColor || 'rgb(22, 33, 62)')
    svg.setAttribute('viewBox', `0 0 ${width} ${height}`)
}

function mountChamferBorder(element) {
    if (element.dataset.chamferBorder === '1' || element.classList.contains('retro-chamfer--shape-only')) {
        return
    }

    element.dataset.chamferBorder = '1'

    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg')
    svg.setAttribute('aria-hidden', 'true')
    svg.classList.add('retro-chamfer__border')

    const fill = document.createElementNS('http://www.w3.org/2000/svg', 'polygon')
    fill.setAttribute('data-chamfer-fill', '')
    svg.appendChild(fill)

    const stroke = document.createElementNS('http://www.w3.org/2000/svg', 'polygon')
    stroke.setAttribute('data-chamfer-stroke', '')
    svg.appendChild(stroke)

    element.prepend(svg)

    const resizeObserver = new ResizeObserver(() => updateChamferBorder(element))
    resizeObserver.observe(element)

    const mutationObserver = new MutationObserver(() => updateChamferBorder(element))
    mutationObserver.observe(element, {
        attributes: true,
        attributeFilter: ['class', 'style'],
    })

    element._chamferBorderCleanup = () => {
        resizeObserver.disconnect()
        mutationObserver.disconnect()
        svg.remove()
        delete element.dataset.chamferBorder
        delete element._chamferBorderCleanup
    }

    updateChamferBorder(element)
}

export function initChamferBorders(root = document) {
    root.querySelectorAll('.retro-chamfer:not(.retro-chamfer--shape-only)').forEach(mountChamferBorder)
}

export function watchChamferBorders() {
    initChamferBorders()

    const observer = new MutationObserver((mutations) => {
        for (const mutation of mutations) {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType !== Node.ELEMENT_NODE) {
                    return
                }

                if (node.matches?.('.retro-chamfer:not(.retro-chamfer--shape-only)')) {
                    mountChamferBorder(node)
                }

                node.querySelectorAll?.('.retro-chamfer:not(.retro-chamfer--shape-only)').forEach(mountChamferBorder)
            })
        }
    })

    observer.observe(document.body, {
        childList: true,
        subtree: true,
    })
}
