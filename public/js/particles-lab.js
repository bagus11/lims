(function () {
    function clamp(value, min, max) {
        return Math.min(max, Math.max(min, value));
    }

    function prefersReducedMotion() {
        try {
            return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        } catch (e) {
            return false;
        }
    }

    class LabParticles {
        constructor(canvas, options) {
            this.canvas = canvas;
            this.ctx = canvas.getContext('2d', { alpha: true });
            this.options = Object.assign(
                {
                    density: 0.00008,
                    minParticles: 45,
                    maxParticles: 140,
                    maxDistance: 140,
                    speed: 0.38,
                    particleMinRadius: 0.9,
                    particleMaxRadius: 2.2,
                    lineWidth: 1,
                    particleColorA: 'rgba(143, 242, 255, 0.92)',
                    particleColorB: 'rgba(23, 155, 174, 0.92)',
                    lineColor: 'rgba(143, 242, 255, 1)',
                    lineOpacity: 0.16,
                    hoverInfluence: 0.22,
                    hoverDistance: 160,
                },
                options || {}
            );

            this.dpr = 1;
            this.width = 0;
            this.height = 0;
            this.particles = [];
            this.animationFrame = null;
            this.running = false;

            this.mouse = { x: -9999, y: -9999, active: false };
            this._onResize = this.resize.bind(this);
            this._onMouseMove = this.onMouseMove.bind(this);
            this._onMouseLeave = this.onMouseLeave.bind(this);
            this._onVisibilityChange = this.onVisibilityChange.bind(this);
        }

        start() {
            if (!this.canvas || !this.ctx) return;
            if (this.running) return;
            this.running = true;

            this.resize();

            window.addEventListener('resize', this._onResize, { passive: true });
            window.addEventListener('mousemove', this._onMouseMove, { passive: true });
            window.addEventListener('mouseleave', this._onMouseLeave, { passive: true });
            document.addEventListener('visibilitychange', this._onVisibilityChange);

            if (prefersReducedMotion()) {
                this.draw();
                return;
            }

            this.loop();
        }

        stop() {
            this.running = false;
            if (this.animationFrame) {
                cancelAnimationFrame(this.animationFrame);
                this.animationFrame = null;
            }
            window.removeEventListener('resize', this._onResize);
            window.removeEventListener('mousemove', this._onMouseMove);
            window.removeEventListener('mouseleave', this._onMouseLeave);
            document.removeEventListener('visibilitychange', this._onVisibilityChange);
        }

        onVisibilityChange() {
            if (!this.running) return;
            if (document.hidden) {
                if (this.animationFrame) {
                    cancelAnimationFrame(this.animationFrame);
                    this.animationFrame = null;
                }
            } else if (!prefersReducedMotion()) {
                this.loop();
            }
        }

        onMouseMove(e) {
            const rect = this.canvas.getBoundingClientRect();
            this.mouse.x = (e.clientX - rect.left) * this.dpr;
            this.mouse.y = (e.clientY - rect.top) * this.dpr;
            this.mouse.active = true;
        }

        onMouseLeave() {
            this.mouse.active = false;
            this.mouse.x = -9999;
            this.mouse.y = -9999;
        }

        resize() {
            const rect = this.canvas.getBoundingClientRect();
            const nextDpr = clamp(window.devicePixelRatio || 1, 1, 2);
            const nextWidth = Math.max(1, Math.floor(rect.width * nextDpr));
            const nextHeight = Math.max(1, Math.floor(rect.height * nextDpr));

            this.dpr = nextDpr;
            this.width = nextWidth;
            this.height = nextHeight;

            this.canvas.width = nextWidth;
            this.canvas.height = nextHeight;

            const targetCount = clamp(
                Math.floor(nextWidth * nextHeight * this.options.density),
                this.options.minParticles,
                this.options.maxParticles
            );

            this.rebuild(targetCount);
            this.draw();
        }

        rebuild(targetCount) {
            const current = this.particles.length;
            if (current > targetCount) {
                this.particles = this.particles.slice(0, targetCount);
                return;
            }

            for (let i = current; i < targetCount; i++) {
                this.particles.push(this.createParticle(true));
            }
        }

        rand(min, max) {
            return min + Math.random() * (max - min);
        }

        createParticle(randomPosition) {
            const radius = this.rand(this.options.particleMinRadius, this.options.particleMaxRadius) * this.dpr;
            const angle = this.rand(0, Math.PI * 2);
            const speed = this.options.speed * this.rand(0.55, 1.25) * this.dpr;
            const color = Math.random() > 0.55 ? this.options.particleColorA : this.options.particleColorB;

            return {
                x: randomPosition ? this.rand(0, this.width) : -radius,
                y: randomPosition ? this.rand(0, this.height) : this.rand(0, this.height),
                vx: Math.cos(angle) * speed,
                vy: Math.sin(angle) * speed,
                r: radius,
                c: color,
                a: this.rand(0.55, 0.95),
            };
        }

        step() {
            const maxDistance = this.options.maxDistance * this.dpr;
            const hoverDistance = this.options.hoverDistance * this.dpr;
            const hoverInfluence = this.options.hoverInfluence * this.dpr;

            for (const p of this.particles) {
                if (this.mouse.active) {
                    const dx = p.x - this.mouse.x;
                    const dy = p.y - this.mouse.y;
                    const dist2 = dx * dx + dy * dy;
                    if (dist2 < hoverDistance * hoverDistance) {
                        const dist = Math.sqrt(dist2) || 1;
                        const push = (1 - dist / hoverDistance) * hoverInfluence;
                        p.vx += (dx / dist) * push;
                        p.vy += (dy / dist) * push;
                    }
                }

                p.x += p.vx;
                p.y += p.vy;

                const speedLimit = this.options.speed * 1.9 * this.dpr;
                p.vx = clamp(p.vx, -speedLimit, speedLimit);
                p.vy = clamp(p.vy, -speedLimit, speedLimit);

                if (p.x < -maxDistance) p.x = this.width + maxDistance;
                if (p.x > this.width + maxDistance) p.x = -maxDistance;
                if (p.y < -maxDistance) p.y = this.height + maxDistance;
                if (p.y > this.height + maxDistance) p.y = -maxDistance;
            }
        }

        draw() {
            const ctx = this.ctx;
            if (!ctx) return;

            ctx.clearRect(0, 0, this.width, this.height);

            const maxDistance = this.options.maxDistance * this.dpr;
            const maxDistance2 = maxDistance * maxDistance;

            ctx.lineWidth = this.options.lineWidth * this.dpr;
            ctx.lineCap = 'round';

            for (let i = 0; i < this.particles.length; i++) {
                const a = this.particles[i];
                for (let j = i + 1; j < this.particles.length; j++) {
                    const b = this.particles[j];
                    const dx = a.x - b.x;
                    const dy = a.y - b.y;
                    const dist2 = dx * dx + dy * dy;
                    if (dist2 > maxDistance2) continue;

                    const dist = Math.sqrt(dist2) || 1;
                    const strength = 1 - dist / maxDistance;
                    const alpha = clamp(this.options.lineOpacity * strength, 0, 0.25);

                    ctx.strokeStyle = this.options.lineColor.replace(/[\d.]+\)$/g, alpha + ')');
                    ctx.beginPath();
                    ctx.moveTo(a.x, a.y);
                    ctx.lineTo(b.x, b.y);
                    ctx.stroke();
                }
            }

            for (const p of this.particles) {
                ctx.globalAlpha = p.a;
                ctx.fillStyle = p.c;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fill();
            }
            ctx.globalAlpha = 1;
        }

        loop() {
            if (!this.running) return;
            this.step();
            this.draw();
            this.animationFrame = requestAnimationFrame(this.loop.bind(this));
        }
    }

    window.LabParticles = LabParticles;

    function autoInit() {
        const canvas = document.getElementById('qc-particles');
        if (!canvas) return;
        const instance = new LabParticles(canvas);
        instance.start();
        window.__qcLabParticles = instance;
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', autoInit, { once: true });
    } else {
        autoInit();
    }
})();
