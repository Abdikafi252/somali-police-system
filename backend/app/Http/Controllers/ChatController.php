class ChatController extends Controller
{
public function index(){
return view('chat.index');
}

public function fetchUsers(){
return User::where('id','!=',auth()->id())
->get()
->map(fn($u)=>[
'id'=>$u->id,
'name'=>$u->name,
'last_seen'=>$u->last_seen_at? $u->last_seen_at->diffForHumans():'Never'
]);
}

public function fetchMessages(Request $r){
if($r->receiver_id){
return Message::where(function($q)use($r){
$q->where('sender_id',auth()->id())
->where('receiver_id',$r->receiver_id);
})->orWhere(function($q)use($r){
$q->where('sender_id',$r->receiver_id)
->where('receiver_id',auth()->id());
})->orderBy('id')->get();
}
return Message::whereNull('receiver_id')->orderBy('id')->get();
}

public function sendMessage(Request $r){
Message::create([
'sender_id'=>auth()->id(),
'receiver_id'=>$r->receiver_id,
'message'=>$r->message
]);
return response()->json(['ok'=>true]);
}
}