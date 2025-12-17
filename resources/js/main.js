import { initSmoothScroll } from './smoothScroll.js'
import { initCardObserver } from './observer.js'

document.addEventListener('DOMContentLoaded', () => {
  initSmoothScroll()
  initCardObserver()
})

// Dashboard Functions
window.toggleSidebar = function() {
  console.log("Toggle sidebar");
  // Logic for sidebar functionality
}

window.openAddPetModal = function() {
  document.getElementById('addPetModal').classList.add('show');
}

window.closeAddPetModal = function() {
  document.getElementById('addPetModal').classList.remove('show');
}

window.openPetDetail = function() {
  console.log("Open pet detail");
  // Logic to open pet details
}

window.selectGender = function(btn, type) {
  // Reset all buttons first
  const buttons = document.querySelectorAll('.gender-btn');
  buttons.forEach(b => {
    b.className = 'gender-btn border-2 border-gray-200 text-gray-600 py-3 rounded-2xl font-semibold flex items-center justify-center space-x-2 hover:border-gray-300';

    // Add specific hover classes based on button type
    if (b.textContent.trim() === 'Male') {
      b.classList.add('hover:border-blue-300', 'hover:bg-blue-50', 'hover:text-blue-600');
    } else {
      b.classList.add('hover:border-pink-300', 'hover:bg-pink-50', 'hover:text-pink-600');
    }
  });

  // Set active style for clicked button
  if (type === 'male') {
    btn.className = 'gender-btn active-male border-2 border-blue-300 bg-blue-50 text-blue-600 py-3 rounded-2xl font-semibold flex items-center justify-center space-x-2';
  } else {
    btn.className = 'gender-btn active-female border-2 border-pink-300 bg-pink-50 text-pink-600 py-3 rounded-2xl font-semibold flex items-center justify-center space-x-2';
  }
}
