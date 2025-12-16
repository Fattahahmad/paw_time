export function initCardObserver() {
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1'
        entry.target.style.transform = 'translateY(0)'
      }
    })
  })

  document.querySelectorAll('.card').forEach(card => {
    card.style.opacity = '0'
    card.style.transform = 'translateY(20px)'
    observer.observe(card)
  })
}
