<?php

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberIndx</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700&family=Orbitron:wght@500;700;900&display=swap');
        
        :root {
            --bg: #03050a;
            --panel: rgba(10, 15, 28, 0.75);
            --border: rgba(0, 255, 196, 0.15);
            --neon: #00ffc8;
            --neon2: #00e0ff;
            --red: #ff4d6a;
            --green: #2eff7a;
            --text: #ccd6f6;
            --dim: #5a6e8c;
            --glow: 0 0 20px rgba(0, 255, 196, 0.3);
            --red-glow: 0 0 20px rgba(255, 77, 106, 0.3);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'JetBrains Mono', monospace;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        
        
        #neuralCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }
        
        
        .scanlines {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(0, 0, 0, 0.03) 2px,
                rgba(0, 0, 0, 0.03) 4px
            );
            pointer-events: none;
            z-index: 1;
            opacity: 0.4;
        }
        
        
        .app {
            max-width: 1400px;
            margin: 0 auto;
            padding: 16px;
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        
        
        .glass-panel {
            background: var(--panel);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--border);
            border-radius: 28px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255,255,255,0.05);
            padding: 18px 22px;
            transition: all 0.2s ease;
        }
        
        
        .header {
            display: flex;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .hex-icon {
            width: 52px;
            height: 52px;
            background: conic-gradient(from 90deg at 50% 50%, var(--neon), #004d3a, var(--neon2));
            clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #000;
            animation: rotateHex 8s linear infinite;
        }
        @keyframes rotateHex { to { transform: rotate(360deg); } }
        .title h1 {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            font-size: 26px;
            letter-spacing: 3px;
            background: linear-gradient(135deg, var(--neon), #00ffaa, var(--neon2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 20px rgba(0,255,200,0.2);
        }
        .title span {
            font-size: 10px;
            letter-spacing: 4px;
            color: var(--dim);
            display: block;
        }
        .header-stats {
            display: flex;
            gap: 10px;
            margin-left: auto;
        }
        .mini-stat {
            background: rgba(0,0,0,0.3);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 10px 16px;
            text-align: center;
            min-width: 80px;
        }
        .mini-stat .val {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 22px;
        }
        .mini-stat .lbl {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--dim);
        }
        
        
        .nav {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        .nav-btn {
            padding: 10px 24px;
            border-radius: 40px;
            border: 1px solid transparent;
            background: rgba(0,0,0,0.4);
            color: var(--text);
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            backdrop-filter: blur(5px);
        }
        .nav-btn:hover {
            border-color: var(--neon);
            box-shadow: var(--glow);
            color: var(--neon);
        }
        .nav-btn.active {
            background: var(--neon);
            color: #000;
            box-shadow: 0 0 30px var(--neon);
            border-color: var(--neon);
        }
        .nav-btn.support-btn {
            margin-left: auto;
            border-color: rgba(255,255,255,0.2);
            color: var(--neon2);
        }
        
        
        .page {
            display: none;
            animation: fadeSlideIn 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .page.active { display: block; }
        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        
        .input-zone {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .input-zone textarea {
            width: 100%;
            height: 170px;
            background: rgba(0,0,0,0.6);
            border: 2px solid var(--border);
            border-radius: 20px;
            color: var(--neon);
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            padding: 18px;
            resize: vertical;
            outline: none;
            line-height: 1.6;
            transition: 0.2s;
        }
        .input-zone textarea:focus {
            border-color: var(--neon);
            box-shadow: 0 0 35px rgba(0,255,196,0.2);
        }
        .input-zone textarea::placeholder {
            color: var(--dim);
            opacity: 0.7;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 12px 28px;
            border-radius: 40px;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 1px;
            cursor: pointer;
            border: none;
            transition: 0.2s;
            text-transform: uppercase;
        }
        .btn-primary {
            background: var(--neon);
            color: #000;
            box-shadow: 0 0 25px rgba(0,255,196,0.5);
        }
        .btn-primary:hover:not(:disabled) {
            background: #00ffb3;
            box-shadow: 0 0 45px rgba(0,255,196,0.8);
            transform: translateY(-2px);
        }
        .btn-primary:disabled {
            background: #1a3a30;
            color: #3a6a5a;
            box-shadow: none;
            cursor: not-allowed;
        }
        .btn-danger {
            background: transparent;
            border: 1px solid var(--red);
            color: var(--red);
        }
        .btn-danger:hover:not(:disabled) {
            background: var(--red);
            color: #fff;
            box-shadow: var(--red-glow);
        }
        .btn-danger:disabled {
            border-color: #5a2a2a;
            color: #5a2a2a;
            cursor: not-allowed;
        }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
        }
        .btn-outline:hover { border-color: var(--neon); color: var(--neon); }
        
        
        .progress-wrap { margin-top: 8px; }
        .progress-bar-bg {
            height: 4px;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--neon), var(--neon2));
            box-shadow: 0 0 20px var(--neon);
            transition: width 0.3s;
            border-radius: 10px;
        }
        .progress-label {
            font-size: 11px;
            margin-top: 5px;
            color: var(--dim);
        }
        
        
        .results-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        .result-panel {
            background: var(--panel);
            backdrop-filter: blur(24px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 16px;
            max-height: 380px;
            overflow-y: auto;
            box-shadow: 0 15px 40px rgba(0,0,0,0.4);
        }
        .result-panel h3 {
            font-family: 'Orbitron', sans-serif;
            font-size: 14px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .badge {
            background: rgba(0,0,0,0.5);
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 10px;
        }
        .result-row {
            padding: 10px 14px;
            margin: 4px 0;
            border-radius: 14px;
            background: rgba(0,0,0,0.4);
            border-left: 4px solid var(--border);
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: slideRow 0.25s ease;
        }
        @keyframes slideRow { from { opacity:0; transform: translateX(-10px); } }
        .result-row.live { border-left-color: var(--green); }
        .result-row.die { border-left-color: var(--red); }
        .result-row .card-number { flex:1; word-break: break-all; }
        .result-row .time { color: var(--dim); font-size: 10px; }
        
        
        .dash-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 14px;
        }
        .dash-card {
            background: var(--panel);
            backdrop-filter: blur(24px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 28px 20px;
            text-align: center;
            transition: 0.2s;
        }
        .dash-card:hover { transform: translateY(-5px); box-shadow: var(--glow); }
        .dash-card .value {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 40px;
            letter-spacing: -2px;
        }
        .dash-card .label {
            color: var(--dim);
            font-size: 11px;
            letter-spacing: 2px;
            margin-top: 10px;
        }
        
        
        .gen-frame {
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            height: 80vh;
        }
        .gen-frame iframe {
            width: 100%;
            height: 100%;
            border: none;
            background: #000;
        }
        
        
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--neon); border-radius: 10px; }
        
        
        .yt-hidden {
            position: fixed;
            bottom: -9999px;
            left: -9999px;
            opacity: 0;
            pointer-events: none;
            z-index: -1;
            width: 0;
            height: 0;
        }
        
        @media (max-width: 768px) {
            .results-container { grid-template-columns: 1fr; }
            .header { flex-direction: column; text-align: center; }
            .header-stats { margin-left: 0; justify-content: center; }
        }
    </style>
</head>
<body>
    <!-- Neural Network Canvas Background -->
    <canvas id="neuralCanvas"></canvas>
    <!-- Scanlines -->
    <div class="scanlines"></div>

    <!-- Main App -->
    <div class="app">
        <!-- Header -->
        <div class="glass-panel header">
            <div class="logo">
                <div class="hex-icon">cybr</div>
                <div class="title">
                    <h1>CYBERINDEX</h1>
                    <span>indexcyb</span>
                </div>
            </div>
            <div class="header-stats">
                <div class="mini-stat"><div class="val" id="hTotal" style="color:#fff">0</div><div class="lbl">Testados</div></div>
                <div class="mini-stat"><div class="val" id="hLive" style="color:var(--green)">0</div><div class="lbl">Lives</div></div>
                <div class="mini-stat"><div class="val" id="hDie" style="color:var(--red)">0</div><div class="lbl">Dies</div></div>
                <div class="mini-stat"><div class="val" id="hRate" style="color:var(--neon)">0%</div><div class="lbl">Taxa</div></div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="glass-panel nav">
            <button class="nav-btn active" onclick="navigate('checker')">🔮 Checker</button>
            <button class="nav-btn" onclick="navigate('dashboard')">📊 Dashboard</button>
            <button class="nav-btn" onclick="navigate('lives')">✅ Lives</button>
            <button class="nav-btn" onclick="navigate('dies')">❌ Dies</button>
            <button class="nav-btn" onclick="navigate('generator')">🎲 Gerador CC</button>
            <button class="nav-btn support-btn" onclick="window.open('https://t.me/cybersecofc','_blank')">💬 Suporte</button>
        </div>

        <!-- PAGES -->
        <!-- Checker -->
        <div id="page-checker" class="page active">
            <div class="glass-panel input-zone">
                <textarea id="cardInput" placeholder="COLE SEUS CARTÕES AQUI&#10;━━━━━━━━━━━━━━━━━━━━━━&#10;Formato: numero|mes|ano|cvv&#10;&#10;Ex: 4220619672458182|12|2032|061"></textarea>
                <div class="btn-group">
                    <button class="btn btn-primary" id="btnStart" onclick="startCheck()">▶ INICIAR</button>
                    <button class="btn btn-danger" id="btnStop" onclick="stopCheck()" disabled>⏹ PARAR</button>
                    <button class="btn btn-outline" onclick="clearAll()">🗑 LIMPAR</button>
                </div>
                <div class="progress-wrap">
                    <div class="progress-bar-bg"><div class="progress-fill" id="progressBar"></div></div>
                    <div class="progress-label" id="progressText">Sistema ocioso...</div>
                </div>
            </div>
            <div class="results-container">
                <div class="result-panel">
                    <h3 style="color:var(--green)">✅ LIVES <span class="badge" id="liveBadge">0</span></h3>
                    <div id="liveResults"></div>
                </div>
                <div class="result-panel">
                    <h3 style="color:var(--red)">❌ DIES <span class="badge" id="dieBadge">0</span></h3>
                    <div id="dieResults"></div>
                </div>
            </div>
        </div>

        <!-- Dashboard -->
        <div id="page-dashboard" class="page">
            <div class="dash-grid">
                <div class="dash-card"><div class="value" id="dTotal" style="color:#fff">0</div><div class="label">TOTAL TESTADOS</div></div>
                <div class="dash-card"><div class="value" id="dLive" style="color:var(--green)">0</div><div class="label">LIVES</div></div>
                <div class="dash-card"><div class="value" id="dDie" style="color:var(--red)">0</div><div class="label">DIES</div></div>
                <div class="dash-card"><div class="value" id="dRate" style="color:var(--neon)">0%</div><div class="label">TAXA DE APROVAÇÃO</div></div>
            </div>
        </div>

        <!-- Lives List -->
        <div id="page-lives" class="page">
            <div class="result-panel" style="max-height:75vh">
                <h3 style="color:var(--green)">✅ Todas as Lives</h3>
                <div id="allLives"></div>
            </div>
        </div>

        <!-- Dies List -->
        <div id="page-dies" class="page">
            <div class="result-panel" style="max-height:75vh">
                <h3 style="color:var(--red)">❌ Todas as Dies</h3>
                <div id="allDies"></div>
            </div>
        </div>

        <!-- Generator -->
        <div id="page-generator" class="page">
            <div class="gen-frame">
                <iframe src="https://cybergerador-de-cc.netlify.app/" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <!-- Hidden YouTube Player for Music -->
    <div class="yt-hidden" id="ytPlayerContainer"></div>

    <!-- YouTube IFrame API -->
    <script src="https://www.youtube.com/iframe_api"></script>
    
    <!-- Neural Network Background Canvas Script -->
    <script>
        (function() {
            const canvas = document.getElementById('neuralCanvas');
            const ctx = canvas.getContext('2d');
            let width, height;
            const particles = [];
            const maxParticles = 100;
            const connectionDist = 130;

            function resize() {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            }
            window.addEventListener('resize', resize);
            resize();

            class Particle {
                constructor() {
                    this.reset();
                }
                reset() {
                    this.x = Math.random() * width;
                    this.y = Math.random() * height;
                    this.vx = (Math.random() - 0.5) * 0.6;
                    this.vy = (Math.random() - 0.5) * 0.6;
                    this.radius = Math.random() * 2 + 1;
                }
                update() {
                    this.x += this.vx;
                    this.y += this.vy;
                    if (this.x < -20 || this.x > width + 20) this.vx *= -1;
                    if (this.y < -20 || this.y > height + 20) this.vy *= -1;
                    this.x = Math.min(Math.max(this.x, -20), width + 20);
                    this.y = Math.min(Math.max(this.y, -20), height + 20);
                }
                draw() {
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                    ctx.fillStyle = 'rgba(0, 255, 196, 0.5)';
                    ctx.fill();
                }
            }

            for (let i = 0; i < maxParticles; i++) {
                particles.push(new Particle());
            }

            function animate() {
                ctx.clearRect(0, 0, width, height);
                
                // connections
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < connectionDist) {
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.strokeStyle = `rgba(0, 255, 196, ${0.08 * (1 - dist/connectionDist)})`;
                            ctx.lineWidth = 0.6;
                            ctx.stroke();
                        }
                    }
                }
                
                // particles
                particles.forEach(p => {
                    p.update();
                    p.draw();
                });
                
                requestAnimationFrame(animate);
            }
            animate();
        })();
    </script>

    <script>
        
        let running = false, cards = [], idx = 0;
        let lives = JSON.parse(localStorage.getItem('cyberLives') || '[]');
        let dies = JSON.parse(localStorage.getItem('cyberDies') || '[]');
        let total = parseInt(localStorage.getItem('cyberTotal') || '0');

        
        function navigate(page) {
            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
            const activeBtn = [...document.querySelectorAll('.nav-btn')].find(b => b.textContent.toLowerCase().includes(page.toLowerCase().substring(0,5)));
            if (activeBtn) activeBtn.classList.add('active');
            document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
            const target = document.getElementById('page-' + page);
            if (target) target.classList.add('active');
            if (page === 'dashboard') refreshDashboard();
        }

        // ============ CHECKING ENGINE ============
        async function startCheck() {
            const input = document.getElementById('cardInput').value.trim();
            if (!input) return alert('Cole seus cartões!');
            cards = input.split('\n').filter(l => l.includes('|')).map(l => l.trim());
            if (!cards.length) return alert('Formato: numero|mes|ano|cvv');
            running = true; idx = 0;
            document.getElementById('btnStart').disabled = true;
            document.getElementById('btnStop').disabled = false;
            processNext();
        }

        function stopCheck() {
            running = false;
            document.getElementById('btnStart').disabled = false;
            document.getElementById('btnStop').disabled = true;
            document.getElementById('progressText').textContent = 'Parado pelo usuário';
        }

        async function processNext() {
            if (!running || idx >= cards.length) {
                document.getElementById('btnStart').disabled = false;
                document.getElementById('btnStop').disabled = true;
                const bar = document.getElementById('progressBar');
                if (idx >= cards.length) bar.style.width = '100%';
                document.getElementById('progressText').textContent = idx >= cards.length ? '✅ Todos processados!' : 'Parado';
                running = false;
                return;
            }
            const card = cards[idx];
            document.getElementById('progressBar').style.width = ((idx+1)/cards.length * 100) + '%';
            document.getElementById('progressText').textContent = `Processando ${idx+1}/${cards.length}...`;
            try {
                const startTime = Date.now();
                const resp = await fetch(`api.php?lista=${encodeURIComponent(card)}`);
                const text = await resp.text();
                total++;
                const elapsed = ((Date.now() - startTime) / 1000).toFixed(2);
                const isLive = /APROVADO|LIVE|APPROVED|CHARGED|Aprovado|sucesso/i.test(text);
                let msg = text;
                try {
                    const parts = text.split('~>');
                    if(parts.length >= 3) msg = parts[2].trim().substring(0, 80);
                } catch(e) {}
                
                const resultObj = { card, msg, time: elapsed };
                if (isLive) {
                    lives.unshift(resultObj);
                    addCardEntry('live', card, msg, elapsed);
                } else {
                    dies.unshift(resultObj);
                    addCardEntry('die', card, msg, elapsed);
                }
                saveData(); refreshUI();
            } catch(e) {
                dies.unshift({ card, msg: 'Erro: ' + e.message, time: '0' });
                addCardEntry('die', card, 'Erro: ' + e.message, '0');
                saveData(); refreshUI();
            }
            idx++;
            setTimeout(processNext, 350); // avoid rate-limiting
        }

        function addCardEntry(type, card, msg, time) {
            const color = type === 'live' ? 'var(--green)' : 'var(--red)';
            const icon = type === 'live' ? '✅' : '❌';
            const html = `
                <div class="result-row ${type}">
                    <span>${icon}</span>
                    <span class="card-number">${card} → ${msg}</span>
                    <span class="time">${time}s</span>
                </div>`;
            document.getElementById(type === 'live' ? 'liveResults' : 'dieResults').insertAdjacentHTML('afterbegin', html);
            document.getElementById(type === 'live' ? 'allLives' : 'allDies').insertAdjacentHTML('afterbegin', html);
        }

        
        function refreshUI() {
            const rate = total > 0 ? ((lives.length / total) * 100).toFixed(1) : 0;
            document.getElementById('hTotal').textContent = total;
            document.getElementById('hLive').textContent = lives.length;
            document.getElementById('hDie').textContent = dies.length;
            document.getElementById('hRate').textContent = rate + '%';
            document.getElementById('liveBadge').textContent = lives.length;
            document.getElementById('dieBadge').textContent = dies.length;
        }

        function refreshDashboard() {
            const rate = total > 0 ? ((lives.length / total) * 100).toFixed(1) : 0;
            document.getElementById('dTotal').textContent = total;
            document.getElementById('dLive').textContent = lives.length;
            document.getElementById('dDie').textContent = dies.length;
            document.getElementById('dRate').textContent = rate + '%';
        }

        function saveData() {
            localStorage.setItem('cyberLives', JSON.stringify(lives.slice(0, 500)));
            localStorage.setItem('cyberDies', JSON.stringify(dies.slice(0, 500)));
            localStorage.setItem('cyberTotal', total);
        }

        function clearAll() {
            if (!confirm('Limpar todos os resultados permanentemente?')) return;
            lives = []; dies = []; total = 0;
            localStorage.clear();
            ['liveResults','dieResults','allLives','allDies'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.innerHTML = '';
            });
            refreshUI(); refreshDashboard();
            document.getElementById('progressBar').style.width = '0%';
            document.getElementById('progressText').textContent = 'Sistema ocioso...';
        }

        
        function onYouTubeIframeAPIReady() {
            new YT.Player('ytPlayerContainer', {
                videoId: 'f7dQ8ESHzjI',  // lofi hip hop radio
                playerVars: {
                    autoplay: 1,
                    loop: 1,
                    playlist: 'f7dQ8ESHzjI',
                    controls: 0,
                    showinfo: 0,
                    rel: 0,
                    modestbranding: 1,
                    volume: 30
                },
                events: {
                    onReady: (event) => event.target.playVideo(),
                    onError: (e) => console.log('YT player error, retrying...')
                }
            });
        }

        
        (function() {
            // Pre-populate lists from storage
            const liveContainer = document.getElementById('allLives');
            const dieContainer = document.getElementById('allDies');
            lives.forEach(l => {
                liveContainer.insertAdjacentHTML('afterbegin', `
                    <div class="result-row live">
                        <span>✅</span>
                        <span class="card-number">${l.card} → ${l.msg}</span>
                        <span class="time">${l.time}s</span>
                    </div>`);
            });
            dies.forEach(d => {
                dieContainer.insertAdjacentHTML('afterbegin', `
                    <div class="result-row die">
                        <span>❌</span>
                        <span class="card-number">${d.card} → ${d.msg}</span>
                        <span class="time">${d.time}s</span>
                    </div>`);
            });
            refreshUI();
            refreshDashboard();
        })();
    </script>
</body>
</html>
