import { WebSocketServer } from 'ws';

const wss = new WebSocketServer({ port: 5000 });

wss.on('connection', function connection(ws) {
    console.log('Client connected');

    ws.on('message', function incoming(message) {
        console.log('Received:', message);
        ws.send(`Server received: ${message}`);
    });

    ws.on('close', function close() {
        console.log('Client disconnected');
    });
});

console.log('WebSocket server running on ws://localhost:5000');
