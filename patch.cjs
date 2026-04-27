const fs = require('fs');
let content = fs.readFileSync('c:\\Users\\doh\\Desktop\\doh\\laravel-app\\resources\\views\\service-requests\\print.blade.php', 'utf8');

content = content.replace(
    /\.sheet \{ max-width: auto; width: 70%; transform: scale\(1\.3\); margin-top: 200px; background: #fff; border-radius: 0; box-shadow: none; padding: 0; \}/,
    '.sheet { width: 210mm !important; max-width: 210mm !important; margin: 100px auto; transform-origin: top center; transform: scale(0.9); background: #fff; border-radius: 0; box-shadow: none; padding: 0; }'
);

content = content.replace(
    /        @endif\r?\n    <\/style>/,
    `        @endif\n        @if (request()->routeIs('service-requests.track.view'))\n            .floating-signature { pointer-events: none !important; cursor: default !important; border-color: transparent !important; }\n            .sig-toolbar { display: none !important; }\n        @endif\n    </style>`
);

content = content.replace(
    /const referenceCode = document\.body\.getAttribute\('data-print-reference'\) \|\| 'default';/,
    `const isReadonlyMode = @json(request()->routeIs('service-requests.track.view'));\n            const referenceCode = document.body.getAttribute('data-print-reference') || 'default';`
);

content = content.replace(
    /const attachNodeEvents = node => \{\r?\n\s+\/\/ Double-click → redraw/,
    `const attachNodeEvents = node => {\n                if (isReadonlyMode) return;\n                // Double-click → redraw`
);

content = content.replace(
    /signatures = loadSignaturesFromStorage\(\);/,
    `signatures = isReadonlyMode ? [] : loadSignaturesFromStorage();`
);

content = content.replace(
    /const applyNodeLayout = \(signature, node\) => \{\r?\n\s+const bounds = getLayerBounds\(\);\r?\n\s+node\.style\.left     = \(signature\.xRatio \* bounds\.width\) \+ 'px';\r?\n\s+node\.style\.top      = \(signature\.yRatio \* bounds\.height\) \+ 'px';/,
    `const applyNodeLayout = (signature, node) => {\n                node.style.left     = (signature.xRatio * 100) + '%';\n                node.style.top      = (signature.yRatio * 100) + '%';`
);

content = content.replace(
    /body: JSON\.stringify\(\{\r?\n\s+signature_data: signatureSource,\r?\n\s+target: normalizedTarget,\r?\n\s+\}\),/,
    `body: JSON.stringify({\n                            signature_data: signatureSource,\n                            target: normalizedTarget,\n                            xRatio: findSignatureById(activeSignatureId)?.xRatio ?? (signatures.length > 0 ? signatures[signatures.length - 1].xRatio : null),\n                            yRatio: findSignatureById(activeSignatureId)?.yRatio ?? (signatures.length > 0 ? signatures[signatures.length - 1].yRatio : null),\n                            scale: findSignatureById(activeSignatureId)?.scale ?? (signatures.length > 0 ? signatures[signatures.length - 1].scale : null),\n                        }),`
);

content = content.replace(
    /window\.__persistedSignatures = \{\r?\n\s+action: @json\(\$actionSigUrl\),\r?\n\s+noted: @json\(\$notedSigUrl\),\r?\n\s+\};/,
    `window.__persistedSignatures = {\n            action: @json($actionSigUrl),\n            actionCoords: {\n                xRatio: @json(data_get($logs, '0.action_sig_x', 0.24)),\n                yRatio: @json(data_get($logs, '0.action_sig_y', 0.55)),\n                scale: @json(data_get($logs, '0.action_sig_scale', 1))\n            },\n            noted: @json($notedSigUrl),\n            notedCoords: {\n                xRatio: @json(data_get($logs, '0.noted_sig_x', 0.65)),\n                yRatio: @json(data_get($logs, '0.noted_sig_y', 0.72)),\n                scale: @json(data_get($logs, '0.noted_sig_scale', 0.9))\n            }\n        };`
);

content = content.replace(
    /if \(typeof window\.__persistedSignatures === 'object' && window\.__persistedSignatures\) \{\r?\n\s+if \(window\.__persistedSignatures\.action !== '' && window\.__persistedSignatures\.action\) \{\r?\n\s+const actionSig = \{\r?\n\s+id: generateSignatureId\(\),\r?\n\s+src: window\.__persistedSignatures\.action,\r?\n\s+xRatio: 0\.24,\r?\n\s+yRatio: 0\.55,\r?\n\s+scale: 1,\r?\n\s+\};\r?\n\s+signatures\.push\(actionSig\);\r?\n\s+\}\r?\n\s+if \(window\.__persistedSignatures\.noted !== '' && window\.__persistedSignatures\.noted\) \{\r?\n\s+const notedSig = \{\r?\n\s+id: generateSignatureId\(\),\r?\n\s+src: window\.__persistedSignatures\.noted,\r?\n\s+xRatio: 0\.65,\r?\n\s+yRatio: 0\.72,\r?\n\s+scale: 0\.9,\r?\n\s+\};\r?\n\s+signatures\.push\(notedSig\);\r?\n\s+\}\r?\n\s+\}/,
    `if (typeof window.__persistedSignatures === 'object' && window.__persistedSignatures) {
                if (window.__persistedSignatures.action !== '' && window.__persistedSignatures.action && !window.__persistedSignatures.action.includes('blank')) {
                    const actionSig = {
                        id: generateSignatureId(),
                        src: window.__persistedSignatures.action,
                        xRatio: parseFloat(window.__persistedSignatures.actionCoords?.xRatio ?? 0.24),
                        yRatio: parseFloat(window.__persistedSignatures.actionCoords?.yRatio ?? 0.55),
                        scale: parseFloat(window.__persistedSignatures.actionCoords?.scale ?? 1),
                    };
                    
                    if (isReadonlyMode || !signatures.some(s => Math.abs(parseFloat(s.xRatio) - actionSig.xRatio) < 0.02 && Math.abs(parseFloat(s.yRatio) - actionSig.yRatio) < 0.02)) {
                        signatures.push(actionSig);
                    }
                }
                if (window.__persistedSignatures.noted !== '' && window.__persistedSignatures.noted && !window.__persistedSignatures.noted.includes('blank')) {
                    const notedSig = {
                        id: generateSignatureId(),
                        src: window.__persistedSignatures.noted,
                        xRatio: parseFloat(window.__persistedSignatures.notedCoords?.xRatio ?? 0.65),
                        yRatio: parseFloat(window.__persistedSignatures.notedCoords?.yRatio ?? 0.72),
                        scale: parseFloat(window.__persistedSignatures.notedCoords?.scale ?? 0.9),
                    };
                    
                    if (isReadonlyMode || !signatures.some(s => Math.abs(parseFloat(s.xRatio) - notedSig.xRatio) < 0.02 && Math.abs(parseFloat(s.yRatio) - notedSig.yRatio) < 0.02)) {
                        signatures.push(notedSig);
                    }
                }
            }`
);

fs.writeFileSync('c:\\Users\\doh\\Desktop\\doh\\laravel-app\\resources\\views\\service-requests\\print.blade.php', content);
content = content.replace(/    <\/script>\r?\n    \r?\n    @endif\r?\n\r?\n    <script>/, '    </script>\n\n    <script>'); fs.writeFileSync('c:\\\\Users\\\\doh\\\\Desktop\\\\doh\\\\laravel-app\\\\resources\\\\views\\\\service-requests\\\\print.blade.php', content);
