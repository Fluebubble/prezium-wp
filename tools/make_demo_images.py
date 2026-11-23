"""Erzeugt Platzhalter-Bilder (TESTBILD) für die Demo-Produkte. Kein Foto, nur Textur."""
import sys, numpy as np
from PIL import Image, ImageDraw, ImageFilter, ImageFont

W, H = 1200, 1500
rng = np.random.default_rng(7)
OUT = sys.argv[1]

def noise(h, w, scale):
    small = rng.random((max(2, h // scale), max(2, w // scale)))
    return np.asarray(Image.fromarray((small * 255).astype('uint8')).resize((w, h), Image.BICUBIC), dtype=float) / 255

def wood(h, w, base, grain=1.0, stretch=24):
    n = noise(h, w // stretch + 2, 6)
    n = np.asarray(Image.fromarray((n * 255).astype('uint8')).resize((w, h), Image.BICUBIC), dtype=float) / 255
    fine = noise(h, w, 3)
    t = 0.55 * n + 0.25 * fine + 0.2 * noise(h, w, 40)
    base = np.array(base, dtype=float)
    img = base[None, None, :] * (0.7 + 0.6 * t[..., None] * grain)
    return np.clip(img, 0, 255)

def save(arr, name, label=True):
    im = Image.fromarray(arr.astype('uint8'))
    if label:
        d = ImageDraw.Draw(im)
        d.rectangle([0, H - 54, 470, H], fill=(20, 22, 24))
        d.text((16, H - 40), "TESTBILD - Platzhalter, kein echtes Foto", fill=(235, 235, 230), font=ImageFont.load_default())
    im.save(f"{OUT}/{name}.jpg", quality=82, optimize=True)

def door(name, color, grooves=True, glass=False, handle=(190, 160, 110)):
    bg = np.full((H, W, 3), (205, 205, 200), dtype=float)
    arr = bg.copy()
    x0, x1, y0, y1 = 300, 900, 120, H - 90
    arr[y0:y1, x0:x1] = wood(y1 - y0, x1 - x0, color, 0.6 if grooves else 0.25, 30)
    if grooves:
        for gx in range(x0 + 100, x1, 100):
            arr[y0 + 60:y1 - 60, gx:gx + 3] *= 0.55
    if glass:
        g = np.linspace(0.85, 1.1, y1 - y0 - 400)[:, None, None] * np.array([150, 175, 185])[None, None, :]
        arr[y0 + 200:y1 - 200, x0 + 90:x1 - 90] = g
    arr[y0 + 650:y0 + 720, x1 - 90:x1 - 40] = handle
    arr[:, :x0 - 20] *= 0.97
    save(np.clip(arr, 0, 255), name)

def parquet(name, color, herring=False):
    arr = np.zeros((H, W, 3))
    if not herring:
        bh = 120
        for i in range(0, H, bh):
            shift = rng.integers(0, 400)
            tone = rng.uniform(0.85, 1.12)
            strip = wood(bh, W + 400, np.array(color) * tone, 1.0, 40)[:, shift:shift + W]
            arr[i:i + bh] = strip[:H - i]
            arr[i:i + 2] *= 0.5
    else:
        tile = 90
        base = wood(H, W, color, 1.0, 20)
        arr = base.copy()
        for y in range(0, H, tile):
            for x in range(0, W, tile * 3):
                k = ((x // (tile * 3)) + (y // tile)) % 2
                arr[y:y + tile, x:x + tile * 3] *= rng.uniform(0.88, 1.1)
                arr[y:y + tile, x:x + 2] *= 0.5
                arr[y:y + 2, x:x + tile * 3] *= 0.5
    save(np.clip(arr, 0, 255), name)

def window(name, frame, sliding=False):
    arr = np.full((H, W, 3), (215, 215, 210), dtype=float)
    fx0, fx1, fy0, fy1 = 200, 1000, 200, 1250
    arr[fy0:fy1, fx0:fx1] = wood(fy1 - fy0, fx1 - fx0, frame, 0.4, 30)
    t = 60
    sky = np.linspace(1.0, 0.7, fy1 - fy0 - 2 * t)[:, None, None] * np.array([175, 195, 210])[None, None, :]
    arr[fy0 + t:fy1 - t, fx0 + t:fx1 - t] = sky
    mid = (fx0 + fx1) // 2
    arr[fy0 + t:fy1 - t, mid - 18:mid + 18] = wood(fy1 - fy0 - 2 * t, 36, frame, 0.4, 10)
    if not sliding:
        ym = (fy0 + fy1) // 2
        arr[ym - 18:ym + 18, fx0 + t:fx1 - t] = wood(36, fx1 - fx0 - 2 * t, frame, 0.4, 10)
    save(np.clip(arr, 0, 255), name)

def deck(name, color, gap=True):
    arr = np.zeros((H, W, 3))
    bh = 170
    for i in range(0, H, bh):
        tone = rng.uniform(0.88, 1.1)
        arr[i:i + bh] = wood(bh, W, np.array(color) * tone, 1.0, 50)[:H - i]
        if gap:
            arr[i:i + 8] = (25, 25, 25)
    save(np.clip(arr, 0, 255), name)

def furniture(name, kind, color):
    arr = np.full((H, W, 3), (222, 220, 214), dtype=float)
    arr[1100:] = (190, 188, 182)
    im = Image.fromarray(arr.astype('uint8'))
    d = ImageDraw.Draw(im)
    c = tuple(int(v) for v in color)
    if kind == 'table':
        d.rectangle([180, 700, 1020, 770], fill=c)
        for lx in (230, 940):
            d.rectangle([lx, 770, lx + 34, 1150], fill=tuple(int(v * 0.8) for v in c))
    else:
        d.rectangle([160, 760, 1040, 1010], fill=c)
        for i in range(4):
            d.rectangle([160 + i * 220, 770, 160 + i * 220 + 4, 1005], fill=tuple(int(v * 0.7) for v in c))
        for lx in (210, 950):
            d.rectangle([lx, 1010, lx + 20, 1130], fill=(40, 40, 40))
    im = im.filter(ImageFilter.GaussianBlur(0.6))
    a = np.asarray(im, dtype=float) * (0.92 + 0.12 * noise(H, W, 30)[..., None])
    save(np.clip(a, 0, 255), name)

def light(name, kind):
    arr = np.full((H, W, 3), (28, 30, 33), dtype=float)
    yy, xx = np.mgrid[0:H, 0:W]
    cx, cy = (W // 2, 700) if kind == 'pendant' else (W // 2, 560)
    glow = np.exp(-(((xx - cx) ** 2 + (yy - cy) ** 2) / (2 * 260 ** 2)))
    arr += glow[..., None] * np.array([230, 190, 120])[None, None, :] * 0.55
    im = Image.fromarray(np.clip(arr, 0, 255).astype('uint8'))
    d = ImageDraw.Draw(im)
    if kind == 'pendant':
        d.line([(cx, 0), (cx, 520)], fill=(15, 15, 15), width=6)
        d.ellipse([cx - 170, 520, cx + 170, 860], fill=(236, 220, 190), outline=(60, 60, 60), width=5)
    else:
        d.rectangle([cx - 20, 380, cx + 20, 900], fill=(15, 15, 15))
        d.rectangle([cx - 60, 380, cx + 60, 410], fill=(236, 220, 190))
    save(np.asarray(im, dtype=float), name)

def panel(name, color, slats=True):
    arr = wood(H, W, color, 0.9, 16)
    if slats:
        for x in range(0, W, 60):
            arr[:, x:x + 8] *= 0.25
    save(np.clip(arr, 0, 255), name)

def stone(name, color):
    base = np.array(color, dtype=float)
    arr = base[None, None, :] * (0.7 + 0.5 * noise(H, W, 8)[..., None] + 0.2 * noise(H, W, 2)[..., None])
    for x in range(0, W, 300):
        arr[:, x:x + 5] *= 0.4
    for y in range(0, H, 300):
        arr[y:y + 5] *= 0.4
    save(np.clip(arr, 0, 255), name)

door('bianco-designtuer', (236, 234, 228), grooves=False)
door('anthrazit-glastuer', (60, 64, 68), grooves=False, glass=True, handle=(200, 200, 200))
door('nussbaum-stiltuer', (95, 62, 40))
parquet('raeuchereiche-dielen', (120, 88, 56))
parquet('fischgraet-nussbaum', (96, 64, 42), herring=True)
window('kunststoff-alu-fenster', (225, 225, 222))
window('hebeschiebetuer-panorama', (50, 54, 58), sliding=True)
deck('bangkirai-terrasse', (150, 82, 50))
deck('wpc-graphit', (72, 74, 78), gap=True)
furniture('esstisch-eiche', 'table', (176, 134, 84))
furniture('sideboard-nussbaum', 'sideboard', (92, 60, 40))
light('pendelleuchte-sfera', 'pendant')
light('wandleuchte-linea', 'wall')
panel('akustikpaneel-eiche', (178, 138, 90))
stone('basalt-fliese', (92, 94, 98))
print('ok')
