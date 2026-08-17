const FOCUSABLE_SELECTOR = 'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'

export function getFocusableElements(container) {
  return Array.from(container.querySelectorAll(FOCUSABLE_SELECTOR)).filter((element) => element.tabIndex !== -1 && !element.hasAttribute('disabled'))
}

export function assignDialogLabel(container, fallbackId) {
  const heading = container.querySelector('h1, h2, h3, h4')

  if (!heading) {
    return null
  }

  if (!heading.id) {
    heading.id = `modal-title-${fallbackId}`
  }

  return heading.id
}

export function focusInitialElement(container) {
  const focusable = getFocusableElements(container)

  if (focusable.length > 0) {
    focusable[0].focus()

    return
  }

  container.focus()
}

export function handleFocusTrapKeydown(event, container) {
  if (event.key !== 'Tab' || !container) {
    return
  }

  const focusable = getFocusableElements(container)

  if (focusable.length === 0) {
    event.preventDefault()
    container.focus()

    return
  }

  const first = focusable[0]
  const last = focusable[focusable.length - 1]

  if (event.shiftKey && document.activeElement === first) {
    event.preventDefault()
    last.focus()
  } else if (!event.shiftKey && document.activeElement === last) {
    event.preventDefault()
    first.focus()
  }
}
