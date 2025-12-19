import './bootstrap';
import './main.js';
import Toastify from 'toastify-js';
import 'toastify-js/src/toastify.css';

// Toast Notification System
window.showToast = function({ type = 'info', title = '', message = '', duration = 3000 }) {
  const types = {
    success: { bg: 'linear-gradient(to right, #10b981, #059669)', icon: '✓' },
    error: { bg: 'linear-gradient(to right, #ef4444, #dc2626)', icon: '✕' },
    warning: { bg: 'linear-gradient(to right, #f59e0b, #d97706)', icon: '⚠' },
    info: { bg: 'linear-gradient(to right, #3b82f6, #2563eb)', icon: 'ℹ' }
  };

  const config = types[type] || types.info;
  const text = title ? `<strong>${config.icon} ${title}</strong><br/>${message}` : `${config.icon} ${message}`;

  // Detect screen size
  const isMobile = window.innerWidth < 768;

  Toastify({
    text: text,
    duration: duration,
    close: true,
    gravity: 'top',
    position: isMobile ? 'center' : 'right',
    stopOnFocus: true,
    escapeMarkup: false,
    style: {
      background: config.bg,
      borderRadius: '12px',
      padding: '12px 20px',
      fontSize: '14px',
      fontWeight: '500',
      boxShadow: '0 4px 12px rgba(0, 0, 0, 0.15)',
    }
  }).showToast();
};
