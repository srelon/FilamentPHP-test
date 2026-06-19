const { WebSocketServer } = require('ws')
const redis = require('./redis')
const logger = require('./logger')

const PORT = process.env.WS_PORT || 6001

const wss = new WebSocketServer({ port: PORT })

logger.info(`WebSocket server started on port ${PORT}`)

wss.on('connection', (ws, req) => {
    logger.info({ ip: req.socket.remoteAddress }, 'Client connected')

    ws.on('message', (data) => {
        logger.debug({ data: data.toString() }, 'Message received')
    })

    ws.on('close', () => {
        logger.info('Client disconnected')
    })

    ws.on('error', (err) => {
        logger.error({ err }, 'WebSocket error')
    })
})
