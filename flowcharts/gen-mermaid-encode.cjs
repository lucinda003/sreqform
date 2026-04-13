const fs = require('fs');
const zlib = require('zlib');

const inputPath = process.argv[2];
const outputPath = process.argv[3];

if (!inputPath || !outputPath) {
  throw new Error('Usage: node gen-mermaid-encode.cjs <input.mmd> <output.txt>');
}

const code = fs.readFileSync(inputPath, 'utf8');
const payload = {
  code,
  mermaid: '{\n  "theme": "default"\n}',
  updateEditor: true,
  autoSync: true,
  updateDiagram: true,
};

const json = JSON.stringify(payload);
const compressed = zlib.deflateSync(Buffer.from(json, 'utf8'));
const b64 = compressed
  .toString('base64')
  .replace(/\+/g, '-')
  .replace(/\//g, '_')
  .replace(/=+$/g, '');

fs.writeFileSync(outputPath, `pako:${b64}`, 'utf8');
