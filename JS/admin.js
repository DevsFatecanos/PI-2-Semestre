// SIDEBAR DROPDOWN
const sidebar = document.getElementById('sidebar');
const allDropdown = document.querySelectorAll('#sidebar .side-dropdown');

allDropdown.forEach(drop => {
  const trigger = drop.parentElement.querySelector('a:first-child');
  if (!trigger) return;
  trigger.addEventListener('click', (e) => {
    e.preventDefault();

    // fecha outros dropdowns abertos
    if (!trigger.classList.contains('active')) {
      allDropdown.forEach(d => {
        const t = d.parentElement.querySelector('a:first-child');
        t?.classList.remove('active');
        d.classList.remove('show');
      });
    }

    // alterna o atual
    trigger.classList.toggle('active');
    drop.classList.toggle('show');
  });
});


/* ---------------------------
   COLAPSAR / EXPANDIR SIDEBAR
---------------------------- */
const toggleSidebar = document.querySelector('nav .toggle-sidebar');
const allSideDivider = document.querySelectorAll('#sidebar .divider');

// função para atualizar texto dos divisores
function atualizarDivisores(colapsado) {
  allSideDivider.forEach(div => {
    if (colapsado) {
      div.textContent = '-';
    } else {
      div.textContent = div.dataset.text || '';
    }
  });
}

// estado inicial
if (sidebar?.classList.contains('hide')) {
  atualizarDivisores(true);
  allDropdown.forEach(d => {
    d.parentElement.querySelector('a:first-child')?.classList.remove('active');
    d.classList.remove('show');
  });
} else {
  atualizarDivisores(false);
}

toggleSidebar?.addEventListener('click', () => {
  sidebar?.classList.toggle('hide');
  const colapsado = sidebar?.classList.contains('hide');
  atualizarDivisores(colapsado);

  // 
  if (colapsado) {
    allDropdown.forEach(d => {
      d.parentElement.querySelector('a:first-child')?.classList.remove('active');
      d.classList.remove('show');
    });
  }
});

// quando mouse sai/entra do sidebar 
sidebar?.addEventListener('mouseleave', function () {
  if (this.classList.contains('hide')) {
    allDropdown.forEach(d => {
      d.parentElement.querySelector('a:first-child')?.classList.remove('active');
      d.classList.remove('show');
    });
    atualizarDivisores(true);
  }
});

sidebar?.addEventListener('mouseenter', function () {
  if (this.classList.contains('hide')) {
    allDropdown.forEach(d => {
      d.parentElement.querySelector('a:first-child')?.classList.remove('active');
      d.classList.remove('show');
    });
    atualizarDivisores(false);
  }
});


/* ---------------------------
   MENUS CONTEXTUAIS (três pontinhos)
   Mantido genérico “Solicitações”/“Minha Conta”
---------------------------- */
const allMenu = document.querySelectorAll('main .content-data .head .menu');

allMenu.forEach(box => {
  const icon = box.querySelector('.icon');
  const menuLink = box.querySelector('.menu-link');
  icon?.addEventListener('click', () => {
    menuLink?.classList.toggle('show');
  });
});

const allProgress = document.querySelectorAll('main .card .progress');
allProgress.forEach(p => {
  if (p.dataset.value) p.style.setProperty('--value', p.dataset.value);
});

(function destacarLinkAtivo() {
  const atual = location.pathname.split('/').pop() || 'index.html';
  const links = document.querySelectorAll('a[href]');
  links.forEach(a => {
    try {
      const href = a.getAttribute('href');
      if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
      const same = href.endsWith(atual);
      a.classList.toggle('active', same);
    } catch {
    }
  });
})();


// elementos do perfil
const profile = document.querySelector('nav .profile');
const dropdownProfile = profile?.querySelector('.profile-link');
const avatarWrapper = document.getElementById('avatarWrapper');
const avatarInput = document.getElementById('avatarInput');
const avatarPreview = document.getElementById('avatarPreview');

// clicar no avatar → abre seletor de imagem (não abre o menu)
if (avatarWrapper && avatarInput && avatarPreview) {
  avatarWrapper.addEventListener('click', (event) => {
    // impede que o clique vá para o .profile (que abre/fecha menu)
    event.stopPropagation();
    avatarInput.click();
  });

  // quando selecionar uma imagem, atualiza o avatar
  avatarInput.addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
      avatarPreview.src = e.target.result;      // mostra a imagem escolhida
      localStorage.setItem('avatarImagem', e.target.result); // persiste no navegador
    };
    reader.readAsDataURL(file);
  });

  // carrega imagem salva, se existir
  const avatarSalvo = localStorage.getItem('avatarImagem');
  if (avatarSalvo) {
    avatarPreview.src = avatarSalvo;
  }
}

// clicar na área do profile (fora do avatar) → abre/fecha menu de opções
profile?.addEventListener('click', (e) => {
  // se clicou no avatar (ou dentro dele), não mexe no menu
  if (e.target.closest('.avatar-wrapper')) return;
  dropdownProfile?.classList.toggle('show');
});


/* ---------------------------
   FECHAR MENUS AO CLICAR FORA
---------------------------- */
window.addEventListener('click', (e) => {
  // fecha dropdown de perfil se clicar fora dele e fora da área de profile
  if (
    dropdownProfile &&
    !e.target.closest('nav .profile')
  ) {
    dropdownProfile.classList.remove('show');
  }

  // fecha menus de três pontinhos
  allMenu.forEach(box => {
    const icon = box.querySelector('.icon');
    const menuLink = box.querySelector('.menu-link');
    if (menuLink && e.target !== icon && !menuLink.contains(e.target)) {
      menuLink.classList.remove('show');
    }
  });
});

/* dados */ 
// Avatar: abrir seletor e fazer preview da imagem

if (avatarWrapper && avatarInput && avatarPreview) {
  avatarButton.addEventListener('click', () => {
    avatarInput.click();
  });

  avatarInput.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (e) => {
      const dataUrl = e.target.result;
      avatarPreview.style.backgroundImage = `url(${dataUrl})`;

      // opcional: guardar no navegador para manter ao recarregar
      localStorage.setItem('cadastroAvatar', dataUrl);
    };
    reader.readAsDataURL(file);
  });

  // carregar avatar salvo, se existir
  const salvo = localStorage.getItem('cadastroAvatar');
  if (salvo) {
    avatarPreview.style.backgroundImage = `url(${salvo})`;
  }
}

// Só para evitar envio real do form (por enquanto)
const form = document.querySelector('.form-dados');
if (form) {
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    alert('Dados salvos');
  });
}