function switchTab(tab) {
      const signupTab = document.querySelector('.tab:first-child');
      const signinTab = document.querySelector('.tab:last-child');
      const signupForm = document.getElementById('signupForm');
      const signinForm = document.getElementById('signinForm');

      if (tab === 'signup') {
        signupTab.classList.add('active');
        signinTab.classList.remove('active');
        signupForm.style.display = 'block';
        signinForm.style.display = 'none';
      } else {
        signupTab.classList.remove('active');
        signinTab.classList.add('active');
        signupForm.style.display = 'none';
        signinForm.style.display = 'block';
      }
    }

    document.getElementById('authForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const activeTab = document.querySelector('.tab.active').textContent.trim();

      if (activeTab === 'Sign Up') {
        const fullName = document.getElementById('fullName').value;
        const email = document.getElementById('email').value;

        if (fullName && email) {
          alert(`Welcome ${fullName}! Your account has been created successfully.`);
          this.reset();
        }
      } else {
        const email = document.getElementById('loginEmail').value;

        if (email) {
          alert(`Welcome back! You have been signed in successfully.`);
          this.reset();
        }
      }
    });

    // Add entrance animation
    window.addEventListener('load', function () {
      const leftSide = document.querySelector('.left-side');
      const rightSide = document.querySelector('.right-side');

      leftSide.style.transform = 'translateX(-100%)';
      rightSide.style.transform = 'translateX(100%)';

      setTimeout(() => {
        leftSide.style.transition = 'transform 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
        rightSide.style.transition = 'transform 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
        leftSide.style.transform = 'translateX(0)';
        rightSide.style.transform = 'translateX(0)';
      }, 100);
    });