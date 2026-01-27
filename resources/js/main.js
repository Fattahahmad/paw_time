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

// Chart Page Functions
window.switchTab = function(tab) {
  const entryTab = document.getElementById('entryTab');
  const chartTab = document.getElementById('chartTab');
  const entryContent = document.getElementById('entryContent');
  const chartContent = document.getElementById('chartContent');

  if (tab === 'entry') {
    entryTab.classList.add('active');
    chartTab.classList.remove('active');
    entryContent.classList.add('active');
    chartContent.classList.remove('active');
  } else {
    chartTab.classList.add('active');
    entryTab.classList.remove('active');
    chartContent.classList.add('active');
    entryContent.classList.remove('active');

    // Initialize chart when switching to chart tab
    if (typeof initChart === 'function') {
      initChart();
    }
  }
}

// Filter and view button handlers for chart page
document.addEventListener('DOMContentLoaded', () => {
  // Filter buttons
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
    });
  });

  // View buttons
  document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
    });
  });

  // Filter chips (Reminder page)
  document.querySelectorAll('.filter-chip').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.filter-chip').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
    });
  });

  // Category buttons (Reminder modal)
  document.querySelectorAll('.category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      this.parentNode.querySelectorAll('.category-btn').forEach(sib => {
        sib.classList.remove('active', 'text-white');
        sib.classList.add('text-gray-600');
      });
      this.classList.add('active');
      this.classList.remove('text-gray-600');
    });
  });

  // Repeat buttons (Reminder modal)
  document.querySelectorAll('.repeat-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      this.parentNode.querySelectorAll('.repeat-btn').forEach(sib => {
        sib.classList.remove('active', 'text-white');
        sib.classList.add('text-gray-600');
      });
      this.classList.add('active');
      this.classList.remove('text-gray-600');
    });
  });
});

// Reminder Page Functions
window.switchReminderTab = function(tabId) {
  // Remove active state from all tabs
  document.querySelectorAll('.reminder-tab-btn').forEach(btn => {
    btn.classList.remove('active');
    btn.classList.add('text-white/80');
    btn.classList.remove('text-white');
    btn.classList.remove('font-semibold');
  });

  // Hide all content sections
  document.querySelectorAll('.content-section').forEach(section => {
    section.classList.remove('active');
  });

  // Activate selected tab
  const activeTab = document.getElementById(tabId + 'Tab');
  if (activeTab) {
    activeTab.classList.add('active', 'font-semibold');
    activeTab.classList.remove('text-white/80');
    activeTab.classList.add('text-white');
  }

  // Show selected content
  const contentSection = document.getElementById(tabId + 'Content');
  if (contentSection) {
    contentSection.classList.add('active');
  }
}

window.openAddReminderModal = function() {
  const modal = document.getElementById('addReminderModal');
  if (modal) {
    modal.classList.add('show');
  }
}

window.closeAddReminderModal = function() {
  const modal = document.getElementById('addReminderModal');
  if (modal) {
    modal.classList.remove('show');
  }
}

window.editReminder = function(reminderId) {
  console.log("Edit reminder:", reminderId);
  // TODO: Populate modal with reminder data and show edit modal
  window.openAddReminderModal();
}

// Store reminder ID for deletion
let reminderToDelete = null;

window.deleteReminder = function(reminderId, title, pet, time, category, note, icon, iconColor) {
  reminderToDelete = reminderId;

  // Populate modal with reminder details
  document.getElementById('deleteConfirmModalTitle').textContent = title;
  document.getElementById('deleteConfirmModalDetail1').textContent = pet;
  document.getElementById('deleteConfirmModalDetail2').textContent = time;
  document.getElementById('deleteConfirmModalDetail3').textContent = category;
  document.getElementById('deleteConfirmModalDetail4').textContent = note;

  // Update icon
  const iconWrapper = document.getElementById('deleteConfirmModalIcon');
  iconWrapper.className = `bg-${iconColor}-100 p-2 rounded-xl`;

  // Show modal
  const modal = document.getElementById('deleteConfirmModal');
  if (modal) {
    modal.classList.add('show');
  }
}

window.closeDeleteConfirmModal = function() {
  const modal = document.getElementById('deleteConfirmModal');
  if (modal) {
    modal.classList.remove('show');
  }
  reminderToDelete = null;
}

window.confirmDeleteConfirmModal = function() {
  if (reminderToDelete) {
    // TODO: Implement actual delete API call here
    console.log("Deleting reminder:", reminderToDelete);

    // Close modal
    window.closeDeleteConfirmModal();

    // Show success toast
    window.showToast({
      type: 'success',
      title: 'Berhasil',
      message: 'Reminder berhasil dihapus',
      duration: 3000
    });

    reminderToDelete = null;
  }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
  const modal = document.getElementById('deleteConfirmModal');
  if (modal && event.target === modal) {
    window.closeDeleteConfirmModal();
  }
});

// Close modal when clicking outside
document.addEventListener('click', function(event) {
  const modal = document.getElementById('addReminderModal');
  if (modal && event.target === modal) {
    window.closeAddReminderModal();
  }
});

// Health Page Functions
window.showDoctorDetail = function() {
  document.getElementById('healthList').classList.remove('active');
  document.getElementById('doctorDetail').classList.add('active');
  window.scrollTo(0, 0);
}

window.showHealthList = function() {
  document.getElementById('doctorDetail').classList.remove('active');
  document.getElementById('healthList').classList.add('active');
  window.scrollTo(0, 0);
}

// Profile Page Functions
window.showDetailProfile = function() {
  document.getElementById('profileList').classList.remove('active');
  document.getElementById('detailProfile').classList.add('active');
  window.scrollTo(0, 0);
}

window.showProfileList = function() {
  document.getElementById('detailProfile').classList.remove('active');
  document.getElementById('profileList').classList.add('active');
  window.scrollTo(0, 0);
}

// ===================================
// ADMIN PANEL FUNCTIONS
// ===================================

// Toggle Admin Sidebar
window.toggleAdminSidebar = function() {
  const sidebar = document.getElementById('adminSidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const icon = document.getElementById('pawToggleIcon');
  
  if (!sidebar) return;
  
  // For desktop: toggle collapsed state
  if (window.innerWidth >= 768) {
    sidebar.classList.toggle('collapsed');
    
    // Rotate paw icon
    if (sidebar.classList.contains('collapsed')) {
      icon.style.transform = 'rotate(180deg)';
    } else {
      icon.style.transform = 'rotate(0deg)';
    }
  } else {
    // For mobile: toggle mobile-show state
    sidebar.classList.toggle('mobile-show');
    
    if (sidebar.classList.contains('mobile-show')) {
      overlay.classList.remove('hidden');
      overlay.classList.add('show');
      document.body.style.overflow = 'hidden';
    } else {
      overlay.classList.add('hidden');
      overlay.classList.remove('show');
      document.body.style.overflow = '';
    }
  }
}

// Close sidebar on window resize
window.addEventListener('resize', function() {
  const sidebar = document.getElementById('adminSidebar');
  const overlay = document.getElementById('sidebarOverlay');
  
  if (!sidebar) return;
  
  if (window.innerWidth >= 768) {
    sidebar.classList.remove('mobile-show');
    overlay.classList.add('hidden');
    overlay.classList.remove('show');
    document.body.style.overflow = '';
  }
});
