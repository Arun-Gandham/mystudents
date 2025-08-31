@extends('superadmin.layouts.auth-layout')

@section('title','Super Admin Login')
@section('content')
<main class="container-fluid min-vh-100 d-flex align-items-center justify-content-center p-3 login-page">
  <div class="row g-0 shadow-lg overflow-hidden login-wrap">
    {{-- Right visual (AI/brain network) --}}
    <div class="col-lg-7 visual-col d-flex align-items-center justify-content-center">
        <div class="neural-wrap text-center">
            <canvas id="neuralCanvas" class="neural-canvas"></canvas>
            <div class="overlay-text mt-3">Super Admin Console</div>
        </div>
    </div>

    {{-- Left form --}}
    <div class="col-lg-5 p-4 p-md-5 form-col">
      <div class="d-flex align-items-center gap-2 mb-3">
        <div class="brand-badge"><i class="bi bi-cpu"></i></div>
        <div>
          <div class="fw-semibold text-uppercase small muted">SMS Control</div>
          <div class="h4 mb-0 title">Sign in</div>
        </div>
      </div>

      {{-- Errors --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <div class="fw-semibold mb-1"><i class="bi bi-exclamation-octagon me-1"></i>Whoops!</div>
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Status (e.g. reset link sent) --}}
      @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ route('superadmin.login.attempt') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label">Email</label>
          <div class="input-group">
            <span class="input-group-text bg-transparent"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="you@domain.com">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label d-flex justify-content-between">
            <span>Password</span>
            <a href="#" class="small text-decoration-none">Forgot password?</a>
          </label>
          <div class="input-group">
            <span class="input-group-text bg-transparent"><i class="bi bi-lock"></i></span>
            <input id="password" type="password" name="password" class="form-control" required placeholder="••••••••">
            <button class="btn btn-outline-secondary" type="button" id="togglePwd" aria-label="Show/Hide password"><i class="bi bi-eye"></i></button>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">Remember me</label>
          </div>
          <span class="text-decoration-none small muted"><i class="bi bi-shield-lock me-1"></i>Security tips</span>
        </div>

        <button type="submit" class="btn btn-brand w-100">
          <i class="bi bi-box-arrow-in-right me-1"></i>Sign in
        </button>

        <div class="text-center small muted mt-3">
          Super Admin access only. Unauthorized use is prohibited.
        </div>
      </form>
    </div>
  </div>
</main>
@endsection

@push('styles')
<style>
  /* Hide your app chrome on this page (optional) */
  .app-header, .app-sidebar { display: none !important; }

  :root{
    --brand: #2563eb;
    --panel: #ffffff;
    --text: #0f172a;
    --text-muted: #6b7280;
  }
  body{
    background: radial-gradient(1200px 800px at 10% -20%, rgba(37,99,235,.18), transparent 60%),
                radial-gradient(1000px 800px at 110% 120%, rgba(16,185,129,.15), transparent 50%),
                #0b1020;
    color: var(--text);
  }
  .login-wrap{ max-width:1120px; width:100%; background:transparent; border-radius:1.25rem; }
  .form-col{ background: var(--panel); min-height:540px; }
  .visual-col{ background: linear-gradient(160deg, rgba(255,255,255,.02), rgba(255,255,255,.02)), transparent; min-height:540px; }
  .brand-badge{
    width:40px;height:40px;border-radius:12px; display:flex;align-items:center;justify-content:center;
    background: color-mix(in srgb, var(--brand) 18%, white); color: var(--brand);
  }
  .title{ color: var(--text); }
  .muted{ color: var(--text-muted); }
  .btn-brand{
    --bs-btn-bg: var(--brand);
    --bs-btn-border-color: var(--brand);
    --bs-btn-hover-bg: color-mix(in srgb, var(--brand) 86%, black);
    --bs-btn-hover-border-color: color-mix(in srgb, var(--brand) 86%, black);
  }
  .form-control, .form-check-input{ background-color: transparent; color: var(--text); }
  .form-control::placeholder{ color:#9aa4b2; }
  .form-control:focus{
    box-shadow: 0 0 0 .2rem color-mix(in srgb, var(--brand) 15%, transparent);
    border-color: color-mix(in srgb, var(--brand) 35%, #ced4da);
  }
  #neuralCanvas{ position:absolute; inset:0; display:block; }
  .overlay-text{
    position:absolute; left:0; right:0; bottom:2rem; text-align:center;
    color:#cbd5e1; letter-spacing:.2rem; text-transform:uppercase; font-weight:600; opacity:.7;
  }
  @media (max-width: 991px){
    .visual-col{ min-height:280px; order:-1; border-radius:1.25rem 1.25rem 0 0; }
    .form-col{ border-radius:0 0 1.25rem 1.25rem; }
  }
  /* Centered neural orb */
.visual-col{
  background: linear-gradient(160deg, rgba(255,255,255,.02), rgba(255,255,255,.02)), transparent;
  min-height:540px;
}
.neural-wrap{
  width: min(520px, 90%);
  aspect-ratio: 1 / 1;
  position: relative;
  display: grid;
  place-items: center;
}
.neural-canvas{
  width: 100%;
  height: 100%;
  border-radius: 50%;
  display: block;
  /* subtle inner glow + vignette */
  box-shadow:
    inset 0 0 60px rgba(37,99,235,.25),
    inset 0 0 120px rgba(16,185,129,.18),
    0 10px 30px rgba(0,0,0,.28);
  background:
    radial-gradient(60% 60% at 50% 50%, rgba(255,255,255,.04), rgba(0,0,0,.0)),
    transparent;
}
/* overlay text now flows below the orb */
.overlay-text{
  position: static;
  color:#cbd5e1;
  letter-spacing:.2rem;
  text-transform:uppercase;
  font-weight:600;
  opacity:.8;
}
@media (max-width: 991px){
  .visual-col{ min-height: 280px; order: -1; border-radius: 1.25rem 1.25rem 0 0; }
  .neural-wrap{ width: min(420px, 92%); }
}

</style>
@endpush

@push('scripts')
<script>
  // Show/Hide password
  document.getElementById('togglePwd').addEventListener('click', ()=>{
    const i = document.getElementById('password');
    const eye = document.querySelector('#togglePwd i');
    if (i.type === 'password'){ i.type='text'; eye.className='bi bi-eye-slash'; }
    else { i.type='password'; eye.className='bi bi-eye'; }
  });

  // Animated neural network background
  const canvas = document.getElementById('neuralCanvas');
  const ctx = canvas.getContext('2d');
  let W=0,H=0, nodes=[], rafId=null;

  function resize(){
    // Size canvas to its own box (neural-wrap), not the full column
    const cssW = canvas.clientWidth;
    const cssH = canvas.clientHeight;
    W = canvas.width  = Math.max(1, Math.floor(cssW * devicePixelRatio));
    H = canvas.height = Math.max(1, Math.floor(cssH * devicePixelRatio));
    }
  function initNodes(){
    const count = Math.max(40, Math.min(110, Math.floor(W/18)));
    nodes = Array.from({length: count}).map(()=>({
      x: Math.random()*W, y: Math.random()*H,
      vx: (Math.random()-.5)*0.3*devicePixelRatio,
      vy: (Math.random()-.5)*0.3*devicePixelRatio
    }));
  }
  function step(){
    ctx.clearRect(0,0,W,H);
    for (const n of nodes){
      n.x += n.vx; n.y += n.vy;
      if (n.x<0||n.x>W) n.vx*=-1;
      if (n.y<0||n.y>H) n.vy*=-1;
    }
    for (let i=0;i<nodes.length;i++){
      for (let j=i+1;j<nodes.length;j++){
        const a=nodes[i], b=nodes[j];
        const dx=a.x-b.x, dy=a.y-b.y;
        const d=Math.hypot(dx,dy);
        if (d<140*devicePixelRatio){
          const alpha = 1 - d/(140*devicePixelRatio);
          ctx.strokeStyle = `rgba(37,99,235,${alpha*0.35})`;
          ctx.lineWidth = 1*devicePixelRatio;
          ctx.beginPath(); ctx.moveTo(a.x,a.y); ctx.lineTo(b.x,b.y); ctx.stroke();
        }
      }
    }
    for (const n of nodes){
      ctx.fillStyle = 'rgba(255,255,255,.8)';
      ctx.beginPath(); ctx.arc(n.x,n.y,1.8*devicePixelRatio,0,Math.PI*2); ctx.fill();
    }
    rafId = requestAnimationFrame(step);
  }
  window.addEventListener('resize', ()=>{
    cancelAnimationFrame(rafId);
    resize(); initNodes(); step();
  }, { passive:true });

  // init
  resize(); initNodes(); step();
</script>
@endpush
