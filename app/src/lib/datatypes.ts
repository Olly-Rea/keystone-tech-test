/** The Tag data structure */
export interface Tag {
  id: number,
  name: string,
}

/** The Bookmark data structure */
export interface Bookmark {
  id: number,
  title: string,
  description: string,
  createdAt: Date,
  url: string,
  urlStatusCode: number,
  tags: Tag[]
}

/** Structure to represent the Bookmark response data */
export interface BookmarkResponse {
  id: number,
  title: string,
  description: string,
  created_at: Date,
  url: string,
  url_status_code: number,
  tags: Tag[]
};