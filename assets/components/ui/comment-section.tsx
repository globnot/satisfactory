"use client"

import * as React from 'react'
import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Textarea } from "@/components/ui/textarea"
import { Avatar, AvatarFallback } from "@/components/ui/avatar"

interface Comment {
    id: number
    nickname: string
    message: string
}

export default function Comment() {
    const [comments, setComments] = useState<Comment[]>([])
    const [nickname, setNickname] = useState("")
    const [message, setMessage] = useState("")

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault()
        if (nickname.trim() && message.trim()) {
            const newComment: Comment = {
                id: Date.now(),
                nickname: nickname.trim(),
                message: message.trim(),
            }
            setComments([...comments, newComment])
            setNickname("")
            setMessage("")
        }
    }

    return (
        <div className="p-4 mx-2 space-y-2 ">
            <h2 className="font-bold">Comments</h2>

            <form onSubmit={handleSubmit} className="space-y-4">
                <Input
                    type="text"
                    placeholder="Your nickname"
                    value={nickname}
                    onChange={(e) => setNickname(e.target.value)}
                    required
                />
                <Textarea
                    placeholder="Write your comment here..."
                    value={message}
                    onChange={(e) => setMessage(e.target.value)}
                    required
                />
                <Button type="submit">Post Comment</Button>
            </form>

            <div className="space-y-4">
                {comments.map((comment) => (
                    <div key={comment.id} className="flex items-start p-4 space-x-4 rounded-lg bg-muted">
                        <Avatar>
                            <AvatarFallback>{comment.nickname[0].toUpperCase()}</AvatarFallback>
                        </Avatar>
                        <div className="flex-1 space-y-1">
                            <p className="font-medium">{comment.nickname}</p>
                            <p className="text-sm text-muted-foreground">{comment.message}</p>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    )
}

export { Comment }