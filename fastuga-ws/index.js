const httpServer = require('http').createServer()
const io = require("socket.io")(httpServer, {
 cors: {
 // The origin is the same as the Vue app domain. Change if necessary
 origin: "http://127.0.0.1:5173",
 methods: ["GET", "POST"],
 credentials: true
 }
})
httpServer.listen(8081, () =>{
 console.log('listening on *:8081')
})
io.on('connection', (socket) => {
 console.log(`client ${socket.id} has connected`)
 socket.on('newOrder', (order) => {
    socket.broadcast.emit('newOrder', order)
})
/*
socket.on('updateOrder', (order) => {
    socket.broadcast.emit('updateOrder', order)
})
socket.on('deleteOrder', (order) => {
    socket.broadcast.emit('deleteOrder', order)
})
*/
socket.on('changeStatusOrder', (order) => {
    socket.broadcast.emit('changeStatusOrder', order)
})
socket.on('loggedIn', function (user) {
    socket.join(user.id)
    if (user.type == 'EM') {
        socket.join('manager')
    }
})
socket.on('loggedOut', function (user) {
    socket.leave(user.id)
    socket.leave('manager')
})
socket.on('updateUser', function (user) {
    socket.in('manager').except(user.id).emit('updateUser', user)
    socket.in(user.id).emit('updateUser', user)
})
})
